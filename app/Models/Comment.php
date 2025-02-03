<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'business_id', 'content', 'parent_id'];

    // Comment belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Comment belongs to a business
    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    // Self-referencing relationship: A comment can have many replies
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    // A comment can optionally have a parent comment
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }
}

