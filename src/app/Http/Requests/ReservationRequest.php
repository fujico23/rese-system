<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    $user = $this->user();
    if ($user) {
      $roleId = $user->role_id;
      return $roleId == 1 || $roleId == 2 || $roleId == 3;
    }
    return false;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'reservation_date' => 'required|date|after_or_equal:today|before_or_equal:2 months',
      'reservation_time' => 'required',
      'number_of_guests' => 'required',
      'course_id' => 'required'
    ];
  }

  public function messages()
  {
    return [
      'reservation_date.required' => '予約日を入力してください',
      'reservation_date.after_or_equal' => '翌日以降の日程で予約して下さい',
      'reservation_date.before_or_equal' => '2ヶ月以内の予約日で設定して下さい',
      'reservation_time.required' => '予約時間を入力してください',
      'number_of_guests.required' => '予約人数を入力してください',
      'course_id.required' => 'コースを選択して下さい'
    ];
  }
}
