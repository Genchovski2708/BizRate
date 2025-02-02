<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'user_id'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function businesses()
    {
        return $this->belongsToMany(Business::class, 'business_category');
    }
}
