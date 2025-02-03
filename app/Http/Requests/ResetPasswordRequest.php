<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'string', 'exists:users,email'],
            'token' => ['required', 'string', 'exists:password_reset_tokens,token'],
            'password' => ['required', 'string', 'confirmed', Password::min(6)->letters()->numbers()->symbols()]
        ];
    }


    public function messages()
    {
        return [
            'email.exists' => 'This email is not recognized!',
            'token.exists' => 'Invalid token, resend reset email!'
        ];
    }
}
