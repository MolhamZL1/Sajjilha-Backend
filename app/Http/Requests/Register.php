<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use function Spatie\FlareClient\__construct;

class Register extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'=>'required|string|max:190',
            'email'=>'required|email|unique:users,email',
            'mobile' => 'required|regex:/^09[0-9]{8}$/|unique:users,mobile',
            'password'=>'required|min:6|max:10|confirmed'
        ];
    }
    public function attributes()
    {
        return [
            'name'=>__('main.name'),
            'email'=>__('main.email'),
            'mobile'=>__('main.mobile'),
            'password'=>__('main.password'),
            'password_confirmation'=>__('main.password_confirmation'),

        ];
    }
}
