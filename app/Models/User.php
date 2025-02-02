<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role'];




    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

// For owned businesses (HasMany relationship)
    public function businesses()
    {
        return $this->hasMany(Business::class);
    }

// For favorited businesses (BelongsToMany relationship)
    public function favoriteBusinesses()
    {
        return $this->belongsToMany(Business::class, 'favorites');
    }


    public function categories()
    {
        return $this->hasMany(Category::class);
    }
    public function isAdmin()
    {
        return $this->role === 'admin';  // Adjust this based on how you store the user's role
    }
}
