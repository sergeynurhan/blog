<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['user', 'title', 'description', 'tags', 'image', 'posted_at'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($post) {
            $post->comments()->delete();
            $post->tags()->detach();
        });
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'posts_tags');
    }
}
