<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileApiController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user('api') ?? $request->user();

        return response_data([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'mobile' => $user->mobile,
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user('api') ?? $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:190'],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'mobile' => [
                'required',
                'regex:/^09[0-9]{8}$/',
                Rule::unique('users', 'mobile')->ignore($user->id),
            ],
            'password' => ['nullable', 'confirmed', 'min:6', 'max:10'],
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->mobile = $data['mobile'];

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return response_data([
            'name' => $user->name,
            'email' => $user->email,
            'mobile' => $user->mobile,
        ], __('main.msg_succes'));
    }
}
