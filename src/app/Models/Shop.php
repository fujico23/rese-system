<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = ['shop_name', 'area_id', 'genre_id', 'description', 'is_active'];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function reviews()
    {
        return $this->hasManyThrough(Review::class, Reservation::class);
    }

    public function averageRating()
    {
        return $this->reviews()
            ->selectRaw('AVG(rating) as average_rating')
            ->groupBy('shops.id');
    }

    public function scopeGenreSearch($query, $genre_id)
    {
        if (!empty($genre_id)) {
            $query->where('genre_id', $genre_id);
        }
    }

    public function scopeAreaSearch($query, $area_id)
    {
        if (!empty($area_id)) {
            $query->where('area_id', $area_id);
        }
    }

    public function scopeKeywordSearch($query, $keyword)
    {
        if (!empty($keyword)) {
            $query->where('shop_name', 'like', '%' . $keyword . '%');
        }
    }
}
