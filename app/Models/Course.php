<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'name_en', 'slug', 'description', 'description_en', 'facilities', 'facilities_en', 'thumbnail'];

    public function localized(string $attribute): mixed
    {
        $value = app()->getLocale() === 'en' && filled($this->getAttribute($attribute.'_en'))
            ? $this->getAttribute($attribute.'_en')
            : $this->getAttribute($attribute);

        return $value;
    }

    protected $casts = [
        'facilities' => 'array',
        'facilities_en' => 'array',
    ];

    protected static function booted(): void
    {
        static::saving(function (Course $course) {
            if (empty($course->slug)) {
                $course->slug = Str::slug($course->name);
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
