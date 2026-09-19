<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class DocumentCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'name_en', 'slug', 'description', 'description_en'];

    public function localized(string $attribute): mixed
    {
        return app()->getLocale() === 'en' && filled($this->getAttribute($attribute.'_en'))
            ? $this->getAttribute($attribute.'_en')
            : $this->getAttribute($attribute);
    }

    protected static function booted(): void
    {
        static::saving(function (DocumentCategory $category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
