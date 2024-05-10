<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['shop_id', 'course_name', 'course_description', 'price', 'course_image'];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
