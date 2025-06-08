<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountRegisterationRequest extends FormRequest
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
            'name'                    => 'required|string|max:255',
            'login_id'                => 'required|unique:m_users|string|regex:/^[a-zA-Z0-9._@-]+$/|max:255',
            'password'                => 'required|confirmed|string|alpha_num|max:32',
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
            'name.required'      => '名前は必須です',
            'name.string'        => '文字形式で入力してください',
            'name.max'           => '255文字以下で入力してください',
            'login_id.required'  => 'ログインIDは必須です',
            'login_id.unique'    => 'このログインＩＤは既に使用されています',
            'login_id.string'    => '文字形式で入力してください',
            'login_id.regex'     => '「英数字」「.」「-」「_」「@」のみ使用できます',
            'login_id.max'       => '255文字以下で入力してください',
            'password.required'  => 'パスワードは必須です',
            'password.confirmed' => '確認用パスワードと一致しません',
            'password.string'    => '文字形式で入力してください',
            'password.alpha_num' => '英数字で入力してください',
            'password.max'       => '255文字以下で入力してください',

        ];
    }
}
