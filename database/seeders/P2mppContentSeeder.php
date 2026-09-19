<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\MenuItem;
use App\Models\Page;
use Illuminate\Database\Seeder;

class P2mppContentSeeder extends Seeder
{
    private const SOURCE = 'https://p2mpp.polinema.ac.id';

    public function run(): void
    {
        $pages = [
            [
                'slug' => 'visi-misi',
                'title' => 'Visi dan Misi',
                'title_en' => 'Vision and Mission',
                'source' => '/tampilan/visimisi',
                'content' => '<h2>Visi</h2><p>Menjadi pusat penjaminan mutu dan pengembangan pembelajaran yang unggul, terpercaya, dan berkelanjutan untuk mendukung Politeknik Negeri Malang.</p><h2>Misi</h2><p>Melaksanakan penjaminan mutu pendidikan secara konsisten, mengembangkan budaya mutu, serta mendukung peningkatan kualitas pembelajaran di Politeknik Negeri Malang.</p>',
                'content_en' => '<h2>Vision</h2><p>To become an excellent, trusted, and sustainable quality assurance and learning development center supporting Politeknik Negeri Malang.</p><h2>Mission</h2><p>To consistently implement educational quality assurance, develop a quality culture, and support continuous improvement of learning quality at Politeknik Negeri Malang.</p>',
            ],
            [
                'slug' => 'kebijakan-mutu',
                'title' => 'Kebijakan Mutu',
                'title_en' => 'Quality Policy',
                'source' => '/tampilan/quality',
                'content' => '<p>Politeknik Negeri Malang (POLINEMA) bertekad untuk memberikan kepuasan kepada para pemangku kepentingan dengan selalu menjaga komitmen yang tinggi terhadap kualitas melalui kebijakan di bidang pendidikan, penelitian, pengabdian kepada masyarakat, kemahasiswaan, serta manajemen, pencitraan, dan layanan.</p>',
                'content_en' => '<p>Politeknik Negeri Malang (POLINEMA) is committed to satisfying its stakeholders by maintaining a strong commitment to quality through policies in education, research, community service, student affairs, management, institutional image, and services.</p>',
            ],
            [
                'slug' => 'tugas-dan-fungsi-utama',
                'title' => 'Tugas dan Fungsi Utama',
                'title_en' => 'Main Duties and Functions',
                'source' => '/tampilan/duties',
                'content' => '<h2>Tugas dan Fungsi Utama</h2><p>P2MPP melaksanakan penjaminan mutu internal dan mendukung pengembangan pembelajaran di lingkungan Politeknik Negeri Malang.</p><h3>Unit kerja</h3><ul><li>Ketua P2MPP</li><li>Sekretariat P2MPP</li><li>Unit Kontrol Dokumen</li><li>Unit Audit Internal</li><li>Unit Pengukuran Statistik Pelanggan</li><li>Staf Administrasi P2MPP</li></ul>',
                'content_en' => '<h2>Main Duties and Functions</h2><p>P2MPP implements internal quality assurance and supports learning development within Politeknik Negeri Malang.</p><h3>Work units</h3><ul><li>Head of P2MPP</li><li>P2MPP Secretariat</li><li>Document Control Unit</li><li>Internal Audit Unit</li><li>Customer Statistics Measurement Unit</li><li>P2MPP Administrative Staff</li></ul>',
            ],
            [
                'slug' => 'standar-kualitas-internal',
                'title' => 'Standar Kualitas Internal',
                'title_en' => 'Internal Quality Standards',
                'source' => '/tampilan/spmi_list',
                'content' => '<p>Standar kualitas internal P2MPP mencakup standar pembelajaran, penelitian, pengabdian kepada masyarakat, dan standar internal derivatif yang mendukung pelaksanaan sistem penjaminan mutu internal.</p><ul><li>8 Standar Pembelajaran</li><li>8 Standar Penelitian</li><li>8 Standar Pengabdian kepada Masyarakat</li><li>12 Standar Internal Derivatif</li></ul>',
                'content_en' => '<p>P2MPP internal quality standards cover learning, research, community service, and derivative internal standards supporting the implementation of the internal quality assurance system.</p><ul><li>8 learning standards</li><li>8 research standards</li><li>8 community service standards</li><li>12 derivative internal standards</li></ul>',
            ],
            [
                'slug' => 'kepuasan-pelanggan',
                'title' => 'Kepuasan Pelanggan',
                'title_en' => 'Customer Satisfaction',
                'source' => '/tampilan/customer',
                'content' => '<p>P2MPP melakukan pengukuran kepuasan pemangku kepentingan sebagai bagian dari evaluasi dan peningkatan mutu berkelanjutan.</p>',
                'content_en' => '<p>P2MPP measures stakeholder satisfaction as part of continuous quality evaluation and improvement.</p>',
            ],
        ];

        foreach ($pages as $data) {
            $source = self::SOURCE.$data['source'];
            $content = $data['content'].'<p><small>Sumber: <a href="'.$source.'">'.$source.'</a></small></p>';
            $contentEn = $data['content_en'].'<p><small>Source: <a href="'.$source.'">'.$source.'</a></small></p>';

            Page::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'title' => $data['title'],
                    'title_en' => $data['title_en'],
                    'content' => $content,
                    'content_en' => $contentEn,
                    'is_published' => true,
                ],
            );
        }

        $category = DocumentCategory::updateOrCreate(
            ['slug' => 'dokumen-p2mpp'],
            [
                'name' => 'Dokumen P2MPP',
                'name_en' => 'P2MPP Documents',
                'description' => 'Dokumen resmi yang dipublikasikan oleh P2MPP.',
                'description_en' => 'Official documents published by P2MPP.',
            ],
        );

        $documents = [
            ['SPMI 2013', 'SPMI_2013.pdf', '/assets/uploads/files/SPMI_2013.pdf'],
            ['SPMI 2015', 'SPMI_2015.pdf', '/assets/uploads/files/SPMI_2015.pdf'],
            ['SPMI 2017', 'SPMI_2017.pdf', '/assets/uploads/files/SPMI_2017.pdf'],
            ['SPMI 2020', 'SPMI_2020.pdf', '/assets/uploads/files/SPMI_2020.pdf'],
            ['SPMI 2021', 'SPMI_2021.pdf', '/assets/uploads/files/SPMI_2021.pdf'],
            ['Sertifikat Akreditasi Institusi 2025', 'sertifikat-akreditasi-institusi-2025.pdf', '/assets/uploads/akreditasi/88a31-ssertifikat-80286-4a9225cd9e2542aaa910dfe344cd1577_sign.pdf'],
        ];

        foreach ($documents as [$title, $fileName, $path]) {
            $url = self::SOURCE.$path;
            Document::updateOrCreate(
                ['file_path' => $url],
                [
                    'document_category_id' => $category->id,
                    'title' => $title,
                    'title_en' => $title,
                    'description' => 'Dokumen resmi P2MPP dari situs sumber.',
                    'description_en' => 'Official P2MPP document from the source website.',
                    'file_name' => $fileName,
                    'file_type' => 'application/pdf',
                    'is_public' => true,
                ],
            );
        }

        $this->syncMenuItems();
        $this->command?->info('Konten P2MPP yang valid berhasil diimpor tanpa konten spam.');
    }

    private function syncMenuItems(): void
    {
        $profile = MenuItem::firstOrCreate(
            ['label' => 'Profil', 'parent_id' => null],
            ['label_en' => 'Profile', 'type' => 'external', 'url' => '#', 'order' => 1],
        );

        foreach ([
            'Visi Misi' => 'visi-misi',
            'Kebijakan Mutu' => 'kebijakan-mutu',
            'Tugas dan Fungsi Utama' => 'tugas-dan-fungsi-utama',
        ] as $label => $slug) {
            $page = Page::where('slug', $slug)->firstOrFail();
            MenuItem::updateOrCreate(
                ['label' => $label, 'parent_id' => $profile->id],
                ['label_en' => $page->title_en, 'type' => 'page', 'page_id' => $page->id],
            );
        }

        $spmi = MenuItem::firstOrCreate(
            ['label' => 'SPMI', 'parent_id' => null],
            ['label_en' => 'Internal Quality Assurance', 'type' => 'external', 'url' => '#', 'order' => 2],
        );
        $page = Page::where('slug', 'standar-kualitas-internal')->firstOrFail();
        MenuItem::updateOrCreate(
            ['label' => 'Standar Kualitas Internal', 'parent_id' => $spmi->id],
            ['label_en' => $page->title_en, 'type' => 'page', 'page_id' => $page->id],
        );
    }
}
