<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = ['shop_id', 'menu_description', 'price', 'menu_image'];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
