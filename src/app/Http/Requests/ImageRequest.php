<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImageRequest extends FormRequest
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
            'review_id' => 'required|exists:reviews,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,PNG|max:4048',
        ];
    }

    public function messages()
    {
        return [
            'images.*.mimes' => 'jpeg,png形式でアップロードして下さい',
            'images.*.max' => '各画像のサイズは4MB以下にして下さい',
        ];
    }
}
