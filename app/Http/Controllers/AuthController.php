<?php

namespace App\Http\Controllers;

use App\Events\EmailVerifyByCode;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Register;
use App\Mail\sendActiveCode;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'forgotPassword', 'verifyResetCode', 'resetPassword', 'ActiveCode', 'ResendActiveCode']]);
    }

    public function ActiveCode(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|exists:users,email',
            'code' => 'required|integer',
        ]);

        $user = User::where('email', $data['email'])->firstOrFail();

        if ($user->email_code != $data['code']) {
            return response_data([], 'Invalid activation code.', 422);
        }

        $user->email_code = null;
        $user->email_verified_at = now();
        $user->save();

        return response_data([], 'Account activated successfully.');
    }

    public function ResendActiveCode(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $data['email'])->firstOrFail();
        $user->email_code = rand(11111, 99999);
        $user->save();

        event(new EmailVerifyByCode($user));

        return response_data([], __('main.code_send_to_email'));
    }

    /**
     * Register a new user.
     */
 public function register(Register $request)
{
    $data = $request->validated();

    // نبحث عن مستخدم بنفس الإيميل أو الموبايل
    $user = User::where('email', $data['email'])
        ->orWhere('mobile', $data['mobile'])
        ->first();

    if ($user) {
        // إذا الحساب موجود بس البريد لسا مو مفعّل
        if ($user->email_verified_at === null) {

            // ✅ هنا النقطة: نحدّث كلمة السر بالكلمة الجديدة
            $user->password    = bcrypt($data['password']);
            $user->email_code  = rand(11111, 99999);
            $user->mobile_code = rand(11111, 99999);
            $user->save();

            event(new EmailVerifyByCode($user));

            return response_data([
                'email' => $user->email,
            ], __('main.code_send_to_email'), 200);
        }

        // لو الحساب موجود ومفعّل فعلاً
        return response_data([], __('main.email_or_mobile_already_taken'), 422);
    }

    // أول مرة تسجيل: ما في user، ننشئ جديد
    $data['password']    = bcrypt($data['password']);
    $data['email_code']  = rand(11111, 99999);
    $data['mobile_code'] = rand(11111, 99999);

    $user = User::create($data);

    event(new EmailVerifyByCode($user));

    return response_data([
        'email' => $user->email,
    ], __('main.code_send_to_email'), 201);
}


    /**
     * Login user.
     */
   public function login(LoginRequest $request)
{
    // 1) نحدد هل اللي دخله المستخدم إيميل ولا موبايل
    $field = filter_var($request->email_or_mobile, FILTER_VALIDATE_EMAIL)
        ? 'email'
        : 'mobile';

    // 2) نجيب المستخدم من قاعدة البيانات
    $user = User::where($field, $request->email_or_mobile)->first();

    if (! $user) {
        // الحساب مش موجود أصلاً
        return response_data([], 'الحساب غير موجود.', 404);
    }

    // 3) نتحقق من كلمة المرور
    if (! Hash::check($request->password, $user->password)) {
        return response_data([], 'كلمة المرور غير صحيحة.', 422);
    }

    // 4) نتأكد إن البريد مفعّل
    if ($user->email_verified_at === null) {
        return response_data([], 'البريد الإلكتروني غير مفعّل، يرجى تفعيل الحساب أولاً.', 403);
    }

    // 5) كل شي تمام → نطلع JWT token للمستخدم
    if (! $token = auth('api')->login($user)) {
        return response_data([], 'حدث خطأ أثناء تسجيل الدخول، حاول مرة أخرى.', 500);
    }

    $data = [];
    $data['token'] = $this->respondWithToken($token);
    $data['user'] = [
        'name'   => $user->name,
        'email'  => $user->email,
        'mobile' => $user->mobile,
    ];

    return response_data($data, __('main.msg_login'), 200);
}

    /**
     * Get the authenticated User.
     */
    public function me()
    {
        $user = auth('api')->user();
        return response_data([
            'name' => $user->name,
            'email' => $user->email,
            'mobile' => $user->mobile,
        ], __('main.msg_medata'), 200);
    }

    /**
     * Log the user out (Invalidate the token).
     */
    public function logout()
    {
        auth('api')->logout();
        return response_data([], __('main.msg_logout'), 200);
    }

    /**
     * Refresh a token.
     */
    public function refresh()
    {
        $token = auth('api')->refresh();
        $data = $this->respondWithToken($token);
        return response_data($data, __('Token refreshed'));
    }

    public function forgotPassword(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $data['email'])->first();
        $code = rand(11111, 99999);

        $user->password_reset_code = $code;
        $user->password_reset_code_expires_at = now()->addMinutes(15);
        $user->password_reset_verified_at = null;
        $user->save();

        Mail::to($user->email)->send(
            new sendActiveCode(
                __('main.reset_password_subject'),
                __('main.msg_code_email', ['code' => $code, 'name' => $user->name])
            )
        );

        return response_data([], __('main.reset_code_sent'));
    }

    public function verifyResetCode(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|exists:users,email',
            'code' => 'required|integer',
        ]);

        $user = User::where('email', $data['email'])->first();

        if (
            empty($user->password_reset_code) ||
            (int) $user->password_reset_code !== (int) $data['code']
        ) {
            return response_data([], __('main.reset_code_invalid'), 422);
        }

        if (
            empty($user->password_reset_code_expires_at) ||
            $user->password_reset_code_expires_at->isPast()
        ) {
            return response_data([], __('main.reset_code_invalid'), 422);
        }

        $user->password_reset_verified_at = now();
        $user->password_reset_code = null;
        $user->password_reset_code_expires_at = null;
        $user->save();

        return response_data([], __('main.reset_code_verified'));
    }

    public function resetPassword(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|max:10|confirmed',
        ]);

        $user = User::where('email', $data['email'])->first();

        if (
            empty($user->password_reset_verified_at) ||
            $user->password_reset_verified_at->lt(now()->subMinutes(30))
        ) {
            return response_data([], __('main.reset_code_not_verified'), 422);
        }

        $user->forceFill([
            'password' => Hash::make($data['password']),
            'remember_token' => Str::random(60),
            'password_reset_verified_at' => null,
            'password_reset_code' => null,
            'password_reset_code_expires_at' => null,
        ])->save();

        event(new PasswordReset($user));

        return response_data([], __('main.password_reset_success'));
    }

    /**
     * Get the token array structure.
     */
    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ];
    }
}
