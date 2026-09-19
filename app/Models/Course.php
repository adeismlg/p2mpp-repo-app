<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'facilities', 'thumbnail'];

    protected $casts = [
        'facilities' => 'array',
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
