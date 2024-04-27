<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
          'name' => 'required|string|max:255',
          'email' => 'required|string|email|max:255|unique:users',
          'password' => 'required|string|min:8|confirmed',
      ];
    }

    public function messages()
    {
      return [
        'email.required' => 'メールアドレスを正しく入力してください',
        'email.email' => 'メールアドレスの形式で入力して下さい',
        'password.required' => 'パスワードを正しく入力して下さい'
      ];
    }
}
