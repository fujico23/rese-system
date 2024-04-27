<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Laravel\Fortify\Fortify;

class LoginFormRequest extends FormRequest
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
            'email' => 'required|email',
            'password' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'email' . '.required' => '登録したメールアドレスを入力してください',
            'email' . '.string' => 'メールアドレスはアドレス形式である必要があります',
            'email' . 'email' => 'メールアドレスはアドレス形式で入力してください',
            'password.required' => '登録したパスワードを入力してください',
            'password.string' => 'パスワードは文字列である必要があります',
        ];
    }
}