<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountUpdatingRequest extends FormRequest
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
        $inputRequest = $this->request;
        $collectRequest = collect($inputRequest);
        $loginUserId = $collectRequest['login_user_id'];
        return [
            'name'                    => 'required|string|max:255',
            'login_id'                => "required|string|max:255|unique:users,login_id,{$loginUserId},id",
            'password'                => 'required|confirmed|string|max:255',
            'icon_image'              => 'nullable|max:255',
            'discription'             => 'nullable|string|max:500',
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
            'login_id.unique'    => 'このログインIDは既に使用されています',
            'login_id.string'    => '文字形式で入力してください',
            'login_id.max'       => '255文字以下で入力してください',
            'password.required'  => 'パスワードは必須です',
            'password.confirmed' => '確認用パスワードと一致しません',
            'password.string'    => '文字形式で入力してください',
            'password.max'       => '255文字以下で入力してください',
            'icon_image.string'  => '文字形式で入力してください',
            'icon_image.max'     => '255文字以下で入力してください',
            'discription.string' => '文字形式で入力してください',
            'discription.max'    => '500文字以下で入力してください',

        ];
    }
}
