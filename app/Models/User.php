<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'metadata_id', 'role'];


    public function metadata()
    {
        return $this->belongsTo(Metadata::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function businesses()
    {
        return $this->belongsToMany(Business::class, 'favorites');
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}
