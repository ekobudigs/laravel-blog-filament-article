<?php

namespace App\Models;


use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'thumnail', 'body', 'active', 'published_at', 'user_id', 'meta_title', 'meta_description'];

    protected $casts = [
        'published_at' => 'datetime'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function shortBody($words = 30)
    {
      return  Str::words(strip_tags($this->body), $words);
    }

    public function getFormattedDate()
    {
        return $this->published_at->format('F jS Y');
    }

    public function getThumbnail()
    {
        if(str_starts_with($this->thumnail, 'http')){
            return $this->thumnail;
        }

        return '/storage/' . $this->thumnail;
    }

    public function humanReadTime(): Attribute
    {
        return new Attribute(
            get: function($value, $attributes){
                $words = Str::wordCount(strip_tags($attributes['body']));
                $minutes = ceil($words /200);

                return $minutes . ' ' . str('min')->plural($minutes) . ', '
                . $words . ' ' . str('words')->plural($words);
            }
        );
    }
}
