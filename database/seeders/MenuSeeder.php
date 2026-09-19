<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use App\Models\Page;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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

        $pageTranslations = [
            'Visi Misi' => ['title' => 'Vision and Mission', 'content' => 'Write P2MPP vision and mission here. Edit it through Admin → Content Pages.'],
            'Kebijakan Mutu' => ['title' => 'Quality Policy', 'content' => 'Write P2MPP quality policy here.'],
            'Tugas dan Fungsi Utama' => ['title' => 'Main Duties and Functions', 'content' => 'Describe P2MPP main duties and functions here.'],
            'Standar Mutu Internal' => ['title' => 'Internal Quality Standards', 'content' => 'List or explain the internal quality standards (SPMI) here.'],
            'Akreditasi Institusi' => ['title' => 'Institutional Accreditation', 'content' => 'Add Polinema institutional accreditation information here.'],
            'Akreditasi Program Studi' => ['title' => 'Study Program Accreditation', 'content' => 'Add accreditation information for each study program here.'],
            'Kepuasan Mahasiswa' => ['title' => 'Student Satisfaction', 'content' => 'Add a summary or link to the student satisfaction survey here.'],
            'Kepuasan Orang Tua' => ['title' => 'Parent Satisfaction', 'content' => 'Add a summary or link to the parent satisfaction survey here.'],
            'Kepuasan Dosen & Staf' => ['title' => 'Lecturer and Staff Satisfaction', 'content' => 'Add a summary or link to the lecturer and education staff satisfaction survey here.'],
            'Kepuasan Mitra' => ['title' => 'Partner Satisfaction', 'content' => 'Add a summary or link to the industry partner satisfaction survey here.'],
            'Kepuasan Alumni' => ['title' => 'Alumni Satisfaction', 'content' => 'Add a summary or link to the alumni satisfaction survey here.'],
        ];

        $pageModels = [];
        foreach ($pages as $title => $content) {
            $page = Page::firstOrCreate(
                ['title' => $title],
                [
                    'slug' => Str::slug($title),
                    'content' => "<p>{$content}</p>",
                    'is_published' => true,
                ]
            );
            $page->update([
                'title_en' => $page->title_en ?: $pageTranslations[$title]['title'],
                'content_en' => $page->content_en ?: "<p>{$pageTranslations[$title]['content']}</p>",
            ]);
            $pageModels[$title] = $page;
        }

        // 2) Susun menu utama + dropdown
        $order = 0;

        $home = MenuItem::firstOrCreate(
            ['label' => 'Beranda', 'parent_id' => null],
            ['label_en' => 'Home', 'type' => 'route', 'route_name' => 'home', 'order' => $order++]
        );

        $profil = MenuItem::firstOrCreate(
            ['label' => 'Profil', 'parent_id' => null],
            ['label_en' => 'Profile', 'type' => 'external', 'url' => '#', 'order' => $order++]
        );
        $this->addChild($profil, 'Visi Misi', $pageModels['Visi Misi']);
        $this->addChild($profil, 'Kebijakan Mutu', $pageModels['Kebijakan Mutu']);
        $this->addChild($profil, 'Tugas dan Fungsi Utama', $pageModels['Tugas dan Fungsi Utama']);

        $spmi = MenuItem::firstOrCreate(
            ['label' => 'SPMI', 'parent_id' => null],
            ['label_en' => 'Internal Quality Assurance', 'type' => 'external', 'url' => '#', 'order' => $order++]
        );
        $this->addChild($spmi, 'Standar Mutu Internal', $pageModels['Standar Mutu Internal']);
        $this->addChild($spmi, 'File SPMI', null, 'documents.index');

        $akreditasi = MenuItem::firstOrCreate(
            ['label' => 'Akreditasi', 'parent_id' => null],
            ['label_en' => 'Accreditation', 'type' => 'external', 'url' => '#', 'order' => $order++]
        );
        $this->addChild($akreditasi, 'Institusi', $pageModels['Akreditasi Institusi']);
        $this->addChild($akreditasi, 'Program Studi', $pageModels['Akreditasi Program Studi']);

        $kepuasan = MenuItem::firstOrCreate(
            ['label' => 'Kepuasan Pelanggan', 'parent_id' => null],
            ['label_en' => 'Customer Satisfaction', 'type' => 'external', 'url' => '#', 'order' => $order++]
        );
        $this->addChild($kepuasan, 'Mahasiswa', $pageModels['Kepuasan Mahasiswa']);
        $this->addChild($kepuasan, 'Orang Tua', $pageModels['Kepuasan Orang Tua']);
        $this->addChild($kepuasan, 'Dosen & Staf Pendidikan', $pageModels['Kepuasan Dosen & Staf']);
        $this->addChild($kepuasan, 'Mitra', $pageModels['Kepuasan Mitra']);
        $this->addChild($kepuasan, 'Alumni', $pageModels['Kepuasan Alumni']);

        MenuItem::firstOrCreate(
            ['label' => 'Pelatihan', 'parent_id' => null],
            ['label_en' => 'Training', 'type' => 'route', 'route_name' => 'courses.index', 'order' => $order++]
        );

        MenuItem::firstOrCreate(
            ['label' => 'Berita', 'parent_id' => null],
            ['label_en' => 'News', 'type' => 'route', 'route_name' => 'news.index', 'order' => $order++]
        );

        MenuItem::firstOrCreate(
            ['label' => 'Repository Dokumen', 'parent_id' => null],
            ['label_en' => 'Document Repository', 'type' => 'route', 'route_name' => 'documents.index', 'order' => $order++]
        );

        $this->syncEnglishLabels();
        $this->command?->info('Menu & halaman default P2MPP berhasil dibuat. Sesuaikan lewat panel admin.');
    }

    private function syncEnglishLabels(): void
    {
        $labels = [
            'Beranda' => 'Home',
            'Profil' => 'Profile',
            'SPMI' => 'Internal Quality Assurance',
            'Akreditasi' => 'Accreditation',
            'Kepuasan Pelanggan' => 'Customer Satisfaction',
            'Pelatihan' => 'Training',
            'Berita' => 'News',
            'Repository Dokumen' => 'Document Repository',
            'Visi Misi' => 'Vision and Mission',
            'Kebijakan Mutu' => 'Quality Policy',
            'Tugas dan Fungsi Utama' => 'Main Duties and Functions',
            'Standar Mutu Internal' => 'Internal Quality Standards',
            'File SPMI' => 'SPMI Files',
            'Institusi' => 'Institution',
            'Program Studi' => 'Study Programs',
            'Mahasiswa' => 'Students',
            'Orang Tua' => 'Parents',
            'Dosen & Staf Pendidikan' => 'Lecturers and Education Staff',
            'Mitra' => 'Partners',
            'Alumni' => 'Alumni',
        ];

        foreach ($labels as $label => $labelEn) {
            MenuItem::where('label', $label)->whereNull('label_en')->update(['label_en' => $labelEn]);
        }

        MenuItem::where('label', 'Beranda')
            ->where('label_en', 'Dashboard')
            ->update(['label_en' => 'Home']);
    }

    private function addChild(MenuItem $parent, string $label, ?Page $page = null, ?string $routeName = null): void
    {
        $englishLabels = [
            'Visi Misi' => 'Vision and Mission',
            'Kebijakan Mutu' => 'Quality Policy',
            'Tugas dan Fungsi Utama' => 'Main Duties and Functions',
            'Standar Mutu Internal' => 'Internal Quality Standards',
            'File SPMI' => 'SPMI Files',
            'Institusi' => 'Institution',
            'Program Studi' => 'Study Programs',
            'Mahasiswa' => 'Students',
            'Orang Tua' => 'Parents',
            'Dosen & Staf Pendidikan' => 'Lecturers and Education Staff',
            'Mitra' => 'Partners',
            'Alumni' => 'Alumni',
        ];
        $labelEn = $englishLabels[$label] ?? $label;
        $order = MenuItem::where('parent_id', $parent->id)->max('order') + 1;

        if ($routeName) {
            MenuItem::firstOrCreate(
                ['label' => $label, 'parent_id' => $parent->id],
                ['label_en' => $labelEn, 'type' => 'route', 'route_name' => $routeName, 'order' => $order]
            );

            return;
        }

        MenuItem::firstOrCreate(
            ['label' => $label, 'parent_id' => $parent->id],
            ['label_en' => $labelEn, 'type' => 'page', 'page_id' => $page?->id, 'order' => $order]
        );
    }
}
