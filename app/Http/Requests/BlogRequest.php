<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogRequest extends FormRequest
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
            'title'                   => 'required|string|max:255',
            'contents'                => 'required|string',
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
            'title.required'      => 'タイトルは必須です',
            'title.string'        => '文字形式で入力してください',
            'title.max'           => '255文字以下で入力してください',
            'contents.required'   => 'コンテンツは必須です',
            'contents.string'     => '文字形式で入力してください',

        ];
    }
}
