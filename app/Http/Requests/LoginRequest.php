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
            'login_id'                => 'required|string|regex:/^[a-zA-Z0-9._@-]+$/|max:255|exists:m_users,login_id',
            'password'                => 'required|string|alpha_num|max:32',
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
            'login_id.regex'     => '「英数字」「.」「-」「_」「@」のみ使用できます',
            'login_id.max'       => '255文字以下で入力してください',
            'login_id.exists'    => 'このログインＩＤは存在しません',
            'password.required'  => 'パスワードを入力してください',
            'password.string'    => '文字形式で入力してください',
            'password.alpha_num' => '英数字で入力してください',
            'password.max'       => '255文字以下で入力してください',

        ];
    }
}
