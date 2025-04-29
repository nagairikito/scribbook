<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'login_id'                => 'required|string|max:255|exists:users,login_id',
            'password'                => 'required|string|max:32',
        ];
    }
    
    /**
     * バリデーションメッセージをカスタマイズする
     *      
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function messages(): array
    {
        return [
            'login_id.required'  => 'ログインIDを入力してください',
            'login_id.string'    => '文字形式で入力してください',
            'login_id.max'       => '255文字以下で入力してください',
            'login_id.exists'    => 'このログインＩＤは存在しません',
            'password.required'  => 'パスワードを入力してください',
            'password.string'    => '文字形式で入力してください',
            'password.max'       => '255文字以下で入力してください',

        ];
    }
}
