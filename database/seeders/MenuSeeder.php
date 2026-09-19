<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use App\Models\Page;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Isi struktur menu default meniru navigasi P2MPP Polinema:
     * Profil, SPMI, Akreditasi, Kepuasan Pelanggan, Pelatihan, dst.
     * Semua bisa diubah/dihapus lagi lewat Admin > Menu Navigasi &
     * Admin > Halaman Konten — ini cuma titik awal.
     *
     * Jalankan dengan:
     *   php artisan db:seed --class=MenuSeeder
     */
    public function run(): void
    {
        // 1) Buat halaman-halaman kontennya dulu
        $pages = [
            'Visi Misi' => 'Tulis visi dan misi P2MPP di sini. Edit lewat Admin &rarr; Halaman Konten.',
            'Kebijakan Mutu' => 'Tulis kebijakan mutu P2MPP di sini.',
            'Tugas dan Fungsi Utama' => 'Jelaskan tugas dan fungsi utama P2MPP di sini.',
            'Standar Mutu Internal' => 'Daftar/penjelasan standar mutu internal (SPMI) di sini.',
            'Akreditasi Institusi' => 'Informasi status akreditasi institusi Polinema di sini.',
            'Akreditasi Program Studi' => 'Informasi status akreditasi tiap program studi di sini.',
            'Kepuasan Mahasiswa' => 'Ringkasan/link survei kepuasan mahasiswa di sini.',
            'Kepuasan Orang Tua' => 'Ringkasan/link survei kepuasan orang tua di sini.',
            'Kepuasan Dosen & Staf' => 'Ringkasan/link survei kepuasan dosen & staf kependidikan di sini.',
            'Kepuasan Mitra' => 'Ringkasan/link survei kepuasan mitra industri di sini.',
            'Kepuasan Alumni' => 'Ringkasan/link survei kepuasan alumni di sini.',
        ];

        $pageModels = [];
        foreach ($pages as $title => $content) {
            $pageModels[$title] = Page::firstOrCreate(
                ['title' => $title],
                ['content' => "<p>{$content}</p>", 'is_published' => true]
            );
        }

        // 2) Susun menu utama + dropdown
        $order = 0;

        $home = MenuItem::firstOrCreate(
            ['label' => 'Beranda', 'parent_id' => null],
            ['type' => 'route', 'route_name' => 'home', 'order' => $order++]
        );

        $profil = MenuItem::firstOrCreate(
            ['label' => 'Profil', 'parent_id' => null],
            ['type' => 'external', 'url' => '#', 'order' => $order++]
        );
        $this->addChild($profil, 'Visi Misi', $pageModels['Visi Misi']);
        $this->addChild($profil, 'Kebijakan Mutu', $pageModels['Kebijakan Mutu']);
        $this->addChild($profil, 'Tugas dan Fungsi Utama', $pageModels['Tugas dan Fungsi Utama']);

        $spmi = MenuItem::firstOrCreate(
            ['label' => 'SPMI', 'parent_id' => null],
            ['type' => 'external', 'url' => '#', 'order' => $order++]
        );
        $this->addChild($spmi, 'Standar Mutu Internal', $pageModels['Standar Mutu Internal']);
        $this->addChild($spmi, 'File SPMI', null, 'documents.index');

        $akreditasi = MenuItem::firstOrCreate(
            ['label' => 'Akreditasi', 'parent_id' => null],
            ['type' => 'external', 'url' => '#', 'order' => $order++]
        );
        $this->addChild($akreditasi, 'Institusi', $pageModels['Akreditasi Institusi']);
        $this->addChild($akreditasi, 'Program Studi', $pageModels['Akreditasi Program Studi']);

        $kepuasan = MenuItem::firstOrCreate(
            ['label' => 'Kepuasan Pelanggan', 'parent_id' => null],
            ['type' => 'external', 'url' => '#', 'order' => $order++]
        );
        $this->addChild($kepuasan, 'Mahasiswa', $pageModels['Kepuasan Mahasiswa']);
        $this->addChild($kepuasan, 'Orang Tua', $pageModels['Kepuasan Orang Tua']);
        $this->addChild($kepuasan, 'Dosen & Staf Pendidikan', $pageModels['Kepuasan Dosen & Staf']);
        $this->addChild($kepuasan, 'Mitra', $pageModels['Kepuasan Mitra']);
        $this->addChild($kepuasan, 'Alumni', $pageModels['Kepuasan Alumni']);

        MenuItem::firstOrCreate(
            ['label' => 'Pelatihan', 'parent_id' => null],
            ['type' => 'route', 'route_name' => 'courses.index', 'order' => $order++]
        );

        MenuItem::firstOrCreate(
            ['label' => 'Berita', 'parent_id' => null],
            ['type' => 'route', 'route_name' => 'news.index', 'order' => $order++]
        );

        MenuItem::firstOrCreate(
            ['label' => 'Repository Dokumen', 'parent_id' => null],
            ['type' => 'route', 'route_name' => 'documents.index', 'order' => $order++]
        );

        $this->command?->info('Menu & halaman default P2MPP berhasil dibuat. Sesuaikan lewat panel admin.');
    }

    private function addChild(MenuItem $parent, string $label, ?Page $page = null, ?string $routeName = null): void
    {
        $order = MenuItem::where('parent_id', $parent->id)->max('order') + 1;

        if ($routeName) {
            MenuItem::firstOrCreate(
                ['label' => $label, 'parent_id' => $parent->id],
                ['type' => 'route', 'route_name' => $routeName, 'order' => $order]
            );

            return;
        }

        MenuItem::firstOrCreate(
            ['label' => $label, 'parent_id' => $parent->id],
            ['type' => 'page', 'page_id' => $page?->id, 'order' => $order]
        );
    }
}
