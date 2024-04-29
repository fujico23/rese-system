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
      'reservation_date' => 'required',
      'reservation_time' => 'required',
      'number_of_guests' => 'required'
    ];
  }

  public function messages()
  {
    return [
      'reservation_date.required' => '予約日を入力してください',
      'reservation_time.required' => '予約時間を入力してください',
      'number_of_guests.required' => '予約人数を入力してください'
    ];
  }
}
