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
            [
                'name' => 'Formulir',
                'slug' => 'formulir',
                'name_en' => 'Forms',
                'description' => 'Formulir dan surat-surat administrasi.',
                'description_en' => 'Administrative forms and letters.',
            ],
            [
                'name' => 'Panduan Akademik',
                'slug' => 'panduan-akademik',
                'name_en' => 'Academic Guides',
                'description' => 'Panduan dan pedoman untuk mahasiswa.',
                'description_en' => 'Guides and handbooks for students.',
            ],
            [
                'name' => 'Sertifikasi',
                'slug' => 'sertifikasi',
                'name_en' => 'Certification',
                'description' => 'Dokumen terkait tes dan sertifikasi bahasa.',
                'description_en' => 'Documents related to language tests and certification.',
            ],
        ];

        foreach ($categories as $category) {
            $model = DocumentCategory::firstOrCreate(['name' => $category['name']], $category);
            $model->update([
                'name_en' => $model->name_en ?: $category['name_en'],
                'description_en' => $model->description_en ?: $category['description_en'],
            ]);
        }

        $courses = [
            [
                'name' => 'Bahasa Inggris',
                'slug' => 'bahasa-inggris',
                'name_en' => 'English',
                'description' => 'Program persiapan TOEIC/TOEFL untuk mahasiswa.',
                'description_en' => 'TOEIC/TOEFL preparation program for students.',
                'facilities' => ['Modul belajar', 'Simulasi ujian', 'Sertifikat kelulusan'],
                'facilities_en' => ['Study modules', 'Exam simulations', 'Completion certificate'],
            ],
            [
                'name' => 'Bahasa Jepang',
                'slug' => 'bahasa-jepang',
                'name_en' => 'Japanese',
                'description' => 'Program persiapan JLPT dari level dasar.',
                'description_en' => 'JLPT preparation program from beginner level.',
                'facilities' => ['Modul belajar', 'Simulasi JLPT N5', 'Sertifikat kelulusan'],
                'facilities_en' => ['Study modules', 'JLPT N5 simulation', 'Completion certificate'],
            ],
        ];

        foreach ($courses as $course) {
            $model = Course::firstOrCreate(['name' => $course['name']], $course);
            $model->update([
                'name_en' => $model->name_en ?: $course['name_en'],
                'description_en' => $model->description_en ?: $course['description_en'],
                'facilities_en' => $model->facilities_en ?: $course['facilities_en'],
            ]);
        }

        $news = News::firstOrCreate(
            ['title' => 'Selamat Datang di Website Baru'],
            [
                'title' => 'Selamat Datang di Website Baru',
                'slug' => 'selamat-datang-di-website-baru',
                'title_en' => 'Welcome to the New Website',
                'excerpt' => 'Website resmi kini hadir dengan fitur repository dokumen.',
                'excerpt_en' => 'The official website is now available with a document repository.',
                'content' => "Kami dengan senang hati meluncurkan website baru, lengkap dengan fitur repository dokumen untuk memudahkan pengunduhan formulir dan panduan.",
                'content_en' => 'We are pleased to launch the new website, complete with a document repository for convenient access to forms and guides.',
                'published_at' => now(),
            ]
        );
        $news->update([
            'title_en' => $news->title_en ?: 'Welcome to the New Website',
            'excerpt_en' => $news->excerpt_en ?: 'The official website is now available with a document repository.',
            'content_en' => $news->content_en ?: 'We are pleased to launch the new website, complete with a document repository for convenient access to forms and guides.',
        ]);
    }
}
