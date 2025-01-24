<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'metadata_id', 'user_id'];

    public function metadata()
    {
        return $this->belongsTo(Metadata::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function businesses()
    {
        return $this->belongsToMany(Business::class, 'business_category');
    }
}
