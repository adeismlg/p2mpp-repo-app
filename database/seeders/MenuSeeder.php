<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use App\Models\Page;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $profile = $this->parentItem('Profil', 'Profile', 1);
        $spmi = $this->parentItem('SPMI', 'Internal Quality Assurance', 2);
        $accreditation = $this->parentItem('Akreditasi', 'Accreditation', 3);
        $polinema = $this->parentItem('Polinema', 'POLINEMA', 4);

        $this->routeItem('Beranda', 'Home', 'home', 0);
        MenuItem::query()
            ->where('parent_id', $profile->id)
            ->where('label', 'Visi Misi')
            ->delete();
        $this->pageItem($profile, 'Kebijakan Mutu', 'Quality Policy', 'kebijakan-mutu', 1);
        $this->pageItem($profile, 'Tugas dan Fungsi Utama', 'Main Duties and Functions', 'tugas-dan-fungsi-utama', 2);
        $this->pageItem($spmi, 'Standar Kualitas Internal', 'Internal Quality Standards', 'standar-kualitas-internal', 0);
        $this->routeItem('File SPMI', 'SPMI Files', 'documents.index', 1, $spmi);
        $this->pageItem($accreditation, 'Institusi', 'Institution', 'akreditasi-institusi', 0);
        $this->pageItem($accreditation, 'Program Studi', 'Study Programs', 'akreditasi-program-studi', 1);
        $this->pageItem($polinema, 'Visi & Misi', 'Vision & Mission', 'visi-misi-polinema', 0);
        $this->pageItem($polinema, 'Jurusan & Program Studi', 'Departments & Study Programs', 'jurusan-polinema', 1);
        $this->pageItem(null, 'Kepuasan Pelanggan', 'Customer Satisfaction', 'kepuasan-pelanggan', 5);
        $this->routeItem('Pelatihan', 'Training', 'courses.index', 6);
        $this->routeItem('Berita', 'News', 'news.index', 7);
        // $this->routeItem('Repository Dokumen', 'Document Repository', 'documents.index', 8);
    }

    private function parentItem(string $label, string $labelEn, int $order): MenuItem
    {
        return MenuItem::updateOrCreate(
            ['label' => $label, 'parent_id' => null],
            [
                'label_en' => $labelEn,
                'type' => 'external',
                'route_name' => null,
                'page_id' => null,
                'url' => '#',
                'order' => $order,
            ],
        );
    }

    private function routeItem(string $label, string $labelEn, string $routeName, int $order, ?MenuItem $parent = null): void
    {
        MenuItem::updateOrCreate(
            ['label' => $label, 'parent_id' => $parent?->id],
            [
                'label_en' => $labelEn,
                'type' => 'route',
                'route_name' => $routeName,
                'page_id' => null,
                'url' => null,
                'order' => $order,
            ],
        );
    }

    private function pageItem(?MenuItem $parent, string $label, string $labelEn, string $slug, int $order): void
    {
        $page = Page::query()->where('slug', $slug)->firstOrFail();

        MenuItem::updateOrCreate(
            ['label' => $label, 'parent_id' => $parent?->id],
            [
                'label_en' => $labelEn,
                'type' => 'page',
                'route_name' => null,
                'page_id' => $page->id,
                'url' => null,
                'order' => $order,
            ],
        );
    }
}
