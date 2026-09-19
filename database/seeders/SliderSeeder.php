<?php

namespace Database\Seeders;

use App\Models\Slider;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class SliderSeeder extends Seeder
{
    public function run(): void
    {
        $sliders = [
            [
                'title' => 'Kegiatan P2MPP',
                'title_en' => 'P2MPP Activities',
                'subtitle' => 'Informasi dan kegiatan terbaru P2MPP Polinema.',
                'subtitle_en' => 'The latest information and activities from P2MPP Polinema.',
                'source' => 'images/home-slider/kegiatan-p2mpp.jpeg',
                'image' => 'sliders/kegiatan-p2mpp.jpeg',
                'order' => 1,
            ],
            [
                'title' => 'Laporan Kepuasan Mahasiswa Tahun 2025',
                'title_en' => '2025 Student Satisfaction Report',
                'subtitle' => 'Lihat ringkasan laporan kepuasan mahasiswa tahun 2025.',
                'subtitle_en' => 'View the summary of the 2025 student satisfaction report.',
                'source' => 'images/home-slider/kepuasan-mahasiswa-2025.png',
                'image' => 'sliders/kepuasan-mahasiswa-2025.png',
                'order' => 2,
            ],
        ];

        foreach ($sliders as $data) {
            if (! Storage::disk('public')->exists($data['image'])) {
                Storage::disk('public')->put(
                    $data['image'],
                    file_get_contents(public_path($data['source']))
                );
            }

            Slider::updateOrCreate(
                ['image' => $data['image']],
                [
                    'title' => $data['title'],
                    'title_en' => $data['title_en'],
                    'subtitle' => $data['subtitle'],
                    'subtitle_en' => $data['subtitle_en'],
                    'order' => $data['order'],
                    'is_active' => true,
                ]
            );
        }

        $this->command?->info('Contoh slider berhasil ditambahkan.');
    }
}
