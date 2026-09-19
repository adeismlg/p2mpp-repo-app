<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class News extends Model
{
    use HasFactory;

    protected $table = 'news';

    protected $fillable = ['title', 'title_en', 'slug', 'excerpt', 'excerpt_en', 'content', 'content_en', 'thumbnail', 'published_at'];

    public function localized(string $attribute): mixed
    {
        return app()->getLocale() === 'en' && filled($this->getAttribute($attribute.'_en'))
            ? $this->getAttribute($attribute.'_en')
            : $this->getAttribute($attribute);
    }

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
