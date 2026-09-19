<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Page extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'title_en', 'slug', 'content', 'content_en', 'is_published'];

    public function localized(string $attribute): mixed
    {
        return app()->getLocale() === 'en' && filled($this->getAttribute($attribute.'_en'))
            ? $this->getAttribute($attribute.'_en')
            : $this->getAttribute($attribute);
    }

    protected $casts = [
        'is_published' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (Page $page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
