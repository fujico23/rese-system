<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'shop_id', 'course_id','reservation_date', 'reservation_time', 'number_of_guests', 'status'];

    // データベースに保存する前に時間の形式を変更するMutator
    public function setReservationTimeAttribute($value)
    {
        $this->attributes['reservation_time'] = date('H:i', strtotime($value));
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
