<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\sendActiveCode;
use App\Models\User;

Route::get('/', function () {
    return response()->json([
        'message' => 'Sajjilha API is running.',
        'documentation' => 'Use the /api routes for all resources.',
    ]);
});

Route::get('/password/reset/{token}', function (string $token) {
    return response()->json([
        'message' => 'Use the API endpoint /api/auth/password/reset to complete the process.',
        'token' => $token,
        'email' => request('email'),
    ]);
})->name('password.reset');

Route::fallback(function () {
    return response()->json([
        'message' => 'Resource not found.',
    ], 404);
});




Route::get('/test-mail', function () {
    $user = User::first(); // أي مستخدم عندك
    $user->email_code = rand(11111, 99999);

    Mail::to("ahmdmrwan47@gmail.com")->send(new sendActiveCode("555", "555"));
    dd(config('mail.default'));

    return 'Mail sent!';
});
