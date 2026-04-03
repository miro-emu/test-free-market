<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
            
            'name' => 'required',
            'image' => 'required | mimes:png,jpeg',
            'description' => 'required | max:225',
            'category_ids' => 'required',
            'condition_id' => 'required',
            'price' => 'required | min:0 | integer ',
        ];
    }

    public function messages(): array
    {
        return [            
            'name.required' => '商品名を入力してください',
            'image.required' => '画像を登録してください',
            'image.mimes' => '「.png」または「.jpeg」形式でアップロードしてください',
            'description.required' => '商品の説明を入力してください', 
            'description.max' => '商品の説明は225字以下で入力してください',
            'category_ids.required' => 'カテゴリーを選択してください',
            'condition_id.required' => '商品の状態を選択してください',
            'price.required' => '販売価格を入力してください',
            'price.min' => '販売価格は0円以上で入力してください',
            'price.integer' => '販売価格は数字形式で入力してください',
        ];
    }
}
