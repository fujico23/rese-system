<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
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
            'comment' => 'required|string|max:400',
            'rating' => 'required',
            'images' => 'nullable|array', // imagesが配列かどうか確認
            'images.*' => 'image|mimes:jpg,jpeg,png,PNG|max:4048', // 各ファイルが画像であるか確認
        ];
    }

    public function messages()
    {
        return [
            'comment.required' => 'コメントは必ず記入して下さい',
            'comment.string' => 'コメントは文字列型で入力して下さい',
            'comment.max' => 'コメントは400文字以内でご記入下さい',
            'rating.required' => '評価は必ず選択して下さい',
            'images.*.mimes' => 'jpeg,png形式でアップロードして下さい',
            'images.*.max' => '各画像のサイズは4MB以下にして下さい', // maxエラーのメッセージを追加
        ];
    }
}
