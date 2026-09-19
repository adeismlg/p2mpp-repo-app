<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\News;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    /**
     * Isi database dengan data contoh (Faker) agar tampilan web
     * terlihat lengkap saat dikembangkan/didemokan.
     *
     * Jalankan dengan:
     *   php artisan db:seed --class=DemoDataSeeder
     *
     * Aman dijalankan berkali-kali untuk kategori & program (pakai
     * nama unik yang sudah ditentukan), tapi setiap kali dijalankan
     * akan MENAMBAH dokumen & berita baru (bukan menimpa).
     */
    public function run(): void
    {
        // 8 kategori dokumen (nama sudah ditentukan di factory, unik)
        $categories = DocumentCategory::factory()->count(8)->create();

        // 6 program/kursus
        Course::factory()->count(6)->create();

        // 15 berita, campuran sudah terbit & masih draf
        News::factory()->count(15)->create();

        // 40 dokumen, tersebar di kategori yang baru dibuat
        Document::factory()
            ->count(40)
            ->create()
            ->each(function (Document $document) use ($categories) {
                $document->update([
                    'document_category_id' => $categories->random()->id,
                ]);
            });

        $this->command?->info('Data demo berhasil dibuat: 8 kategori, 6 program, 15 berita, 40 dokumen.');
    }
}
