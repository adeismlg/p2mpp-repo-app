<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class News extends Model
{
    use HasFactory;

    protected $table = 'news';

    protected $fillable = ['title', 'slug', 'excerpt', 'content', 'thumbnail', 'published_at'];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saving(function (News $news) {
            if (empty($news->slug)) {
                $news->slug = Str::slug($news->title).'-'.Str::random(5);
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
