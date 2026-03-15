<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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
            // 'name' => 'required | max:20',
            // 'image' => 'required | mimes:png,jpeg',
            'postal_code' => 'required | regex:/^\d{3}-\d{4}$/',
            'address_line' => 'required'
        ];
    }

    public function messages(): array
    {
        return [            
            // 'name.required' => 'お名前を入力してください',
            // 'name.max:20' => 'お名前は20字以下で入力してください',            
            // 'image.required' => '画像を選択してください',
            // 'image.mimes' => '「.png」または「.jpeg」形式でアップロードしてください',
            'postal_code.required' => '郵便番号を入力してください',
            'postal_code.regex' => '郵便番号は「123-4567」の形式で入力してください',
            'address_line.required' => '住所を入力してください'
        ];
    }
}