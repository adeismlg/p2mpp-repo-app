<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\DocumentCategory;
use App\Models\News;
use Illuminate\Database\Seeder;

class DocumentRepositorySeeder extends Seeder
{
    /**
     * Contoh data awal. Jalankan dengan:
     * php artisan db:seed --class=DocumentRepositorySeeder
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Formulir', 'description' => 'Formulir dan surat-surat administrasi.'],
            ['name' => 'Panduan Akademik', 'description' => 'Panduan dan pedoman untuk mahasiswa.'],
            ['name' => 'Sertifikasi', 'description' => 'Dokumen terkait tes dan sertifikasi bahasa.'],
        ];

        foreach ($categories as $category) {
            DocumentCategory::firstOrCreate(['name' => $category['name']], $category);
        }

        $courses = [
            [
                'name' => 'Bahasa Inggris',
                'description' => 'Program persiapan TOEIC/TOEFL untuk mahasiswa.',
                'facilities' => ['Modul belajar', 'Simulasi ujian', 'Sertifikat kelulusan'],
            ],
            [
                'name' => 'Bahasa Jepang',
                'description' => 'Program persiapan JLPT dari level dasar.',
                'facilities' => ['Modul belajar', 'Simulasi JLPT N5', 'Sertifikat kelulusan'],
            ],
        ];

        foreach ($courses as $course) {
            Course::firstOrCreate(['name' => $course['name']], $course);
        }

        News::firstOrCreate(
            ['title' => 'Selamat Datang di Website Baru'],
            [
                'title' => 'Selamat Datang di Website Baru',
                'excerpt' => 'Website resmi kini hadir dengan fitur repository dokumen.',
                'content' => "Kami dengan senang hati meluncurkan website baru, lengkap dengan fitur repository dokumen untuk memudahkan pengunduhan formulir dan panduan.",
                'published_at' => now(),
            ]
        );
    }
}
