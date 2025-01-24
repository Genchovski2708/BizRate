<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'business_id', 'metadata_id'];

    /**
     * Get the user associated with the favorite.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the business associated with the favorite.
     */
    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * Get the metadata associated with the favorite.
     */
    public function metadata()
    {
        return $this->belongsTo(Metadata::class);
    }
}
