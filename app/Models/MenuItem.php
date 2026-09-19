<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Route;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'label',
        'type',
        'route_name',
        'page_id',
        'url',
        'open_in_new_tab',
        'order',
    ];

    protected $casts = [
        'open_in_new_tab' => 'boolean',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('order');
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    /**
     * Rute internal yang boleh dipilih admin waktu bikin menu item
     * bertipe 'route'. Tambah di sini kalau ada halaman baru.
     */
    public static function availableRoutes(): array
    {
        return [
            'home' => 'Beranda',
            'news.index' => 'Berita',
            'courses.index' => 'Pelatihan',
            'documents.index' => 'Repository Dokumen',
        ];
    }

    public function resolvedUrl(): string
    {
        return match ($this->type) {
            'route' => Route::has($this->route_name) ? route($this->route_name) : '#',
            'page' => $this->page ? route('pages.show', $this->page) : '#',
            default => $this->url ?? '#',
        };
    }
}
