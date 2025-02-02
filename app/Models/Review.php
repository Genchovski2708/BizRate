<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Review extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'business_id', 'rating', 'comment'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
    public static function boot()
    {
        parent::boot();

        static::saved(function ($review) {
            $business = $review->business;
            $business->average_rating = $business->reviews()->avg('rating');
            $business->save();
        });

        static::deleted(function ($review) {
            $business = $review->business;
            $business->average_rating = $business->reviews()->avg('rating') ?? 0;
            $business->save();
        });
    }
}
