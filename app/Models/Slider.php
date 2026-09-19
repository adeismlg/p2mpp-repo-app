<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'title_en',
        'subtitle',
        'subtitle_en',
        'image',
        'link_url',
        'button_label',
        'button_label_en',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function localized(string $attribute): mixed
    {
        return app()->getLocale() === 'en' && filled($this->getAttribute($attribute.'_en'))
            ? $this->getAttribute($attribute.'_en')
            : $this->getAttribute($attribute);
    }
}
