<?php

namespace App\Http\Controllers;

use App\Events\EmailVerifyByCode;
use App\Events\MobileVerifyByCode;
use App\Http\Requests\Register;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    public function ActiveCode(Request $request)
    {
        $data = $request->validate(['code_type' => 'required|in:email,mobile', 'code' => 'required|integer']);

        if ($request->code_type == 'email') {
            if (auth('api')->user()->email_code == $request->code){
                auth('api')->user()->email_code = null;
                auth('api')->user()->email_verified_at = now();
                auth('api')->user()->save();
                $message = 'تم التحقق';
            } else {
                $message = 'لم يتم التحقق';
            }
        } elseif ($request->code_type == 'mobile') {
            if (auth('api')->user()->mobile_code == $request->code){
                auth('api')->user()->mobile_code = null;
                auth('api')->user()->mobile_verified_at = now();
                auth('api')->user()->save();
                $message = 'تم التحقق';
            } else {
                $message = 'لم يتم التحقق';
            }
        }

        return response_data([], $message);
    }

    public function ResendActiveCode(Request $request)
    {
        $data = $request->validate(['code_type' => 'required|in:email,mobile']);

        if ($request->code_type == 'mobile') {
            event(new MobileVerifyByCode(User::find(auth('api')->user()->id)));
        } elseif ($request->code_type == 'email') {
            event(new EmailVerifyByCode(User::find(auth('api')->user()->id)));
        }

        $message = __('main.code_send_to_email');
        return response_data([], $message);
    }

    /**
     * Register a new user
     */
    public function register(Register $request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt($request->password);
        $data['email_code'] = rand(11111, 99999);
        $data['mobile_code'] = rand(11111, 99999);

        $user = User::create($data);
        $credentials = ['email' => $user->email, 'password' => $request->password];

        return $this->login($credentials);
    }

    /**
     * Login user
     */
    public function login(array $creden = null)
    {
        $credentials = [
            'password' => request('password')
        ];

        if (filter_var(request('email_or_mobile'), FILTER_VALIDATE_EMAIL)) {
            $credentials['email'] = request('email_or_mobile');
        } else {
            $credentials['mobile'] = request('email_or_mobile');
        }

        $attempt = !empty($creden) ? $creden : $credentials;

        if (! $token = auth('api')->attempt($attempt)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $data = [];
        $data['token'] = $this->respondWithToken($token);
        $data['need_mobile_or_email_verified'] = (auth('api')->user()->mobile_verified_at == null || auth('api')->user()->email_verified_at == null);

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
            'mobile' => $user->mobile
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

    /**
     * Get the token array structure.
     */
    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60 // إرجاع البيانات فقط، ليس response
        ];
    }
}
