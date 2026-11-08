<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_default_seed_omits_demo_records_and_publishes_p2mpp_pages(): void
    {
        Storage::fake('public');

        $this->seed();

        $this->assertDatabaseCount('courses', 4);
        $this->assertDatabaseCount('news', 0);
        $this->assertDatabaseCount('document_categories', 1);
        $this->assertDatabaseCount('documents', 9);
        $this->assertDatabaseMissing('users', ['email' => 'test@example.com']);
        $this->assertDatabaseHas('pages', [
            'slug' => 'visi-misi',
            'is_published' => false,
        ]);
        $this->assertDatabaseMissing('menu_items', ['label' => 'Visi Misi']);
        $this->assertDatabaseHas('pages', [
            'slug' => 'akreditasi-institusi',
            'title' => 'Akreditasi Institusi',
            'is_published' => true,
        ]);
        $this->assertDatabaseHas('pages', [
            'slug' => 'visi-misi-polinema',
            'is_published' => true,
        ]);
        $this->assertDatabaseHas('pages', [
            'slug' => 'jurusan-polinema',
            'is_published' => true,
        ]);
        $this->assertDatabaseHas('pages', [
            'slug' => 'standar-kualitas-internal',
            'title' => 'Standar Kualitas Internal',
        ]);
        $this->assertDatabaseHas('courses', [
            'slug' => 'applied-approach',
            'name' => 'Applied Approach',
        ]);
        $this->assertDatabaseHas('menu_items', [
            'label' => 'Institusi',
            'type' => 'page',
        ]);
        $this->assertDatabaseHas('menu_items', [
            'label' => 'Polinema',
            'parent_id' => null,
        ]);
        $this->assertDatabaseHas('menu_items', [
            'label' => 'Jurusan & Program Studi',
        ]);
    }

    public function test_only_pages_with_verified_source_content_are_public(): void
    {
        Storage::fake('public');
        $this->seed();

        $this->get(route('pages.show', 'akreditasi-institusi'))
            ->assertOk()
            ->assertSee('Unggul')
            ->assertSee('situs resmi P2MPP')
            ->assertDontSee('slot gacor');

        $this->get(route('pages.show', 'visi-misi'))
            ->assertNotFound();

        $this->get(route('pages.show', 'visi-misi-polinema'))
            ->assertOk()
            ->assertSee('Menjadi Lembaga Pendidikan Tinggi Vokasi yang Unggul dalam Persaingan Global')
            ->assertSee('Menyelenggarakan dan mengembangkan pendidikan tinggi vokasi yang unggul, inovatif, dan berdaya saing global')
            ->assertSee('Menyelenggarakan penelitian terapan dan pengabdian kepada masyarakat')
            ->assertSee('Menyelenggarakan sistem pengelolaan pendidikan dengan berdasar pada prinsip-prinsip tata kelola yang baik')
            ->assertSee('Mengembangkan kerja sama yang saling menguntungkan')
            ->assertSee('border-l-4', false)
            ->assertSee('bg-sky-50', false)
            ->assertSee('<blockquote', false)
            ->assertSee('Visi dan Misi Polinema')
            ->assertDontSee('Profil Polinema')
            ->assertDontSee('Arah dan komitmen Polinema dalam pendidikan')
            ->assertSee('situs resmi Polinema')
            ->assertDontSee('slot gacor');

        $this->get(route('pages.show', 'jurusan-polinema'))
            ->assertOk()
            ->assertSee('9 Februari 1982')
            ->assertSee('46 program studi')
            ->assertSee('11 program studi D-III')
            ->assertSee('Pamekasan')
            ->assertSee('Teknik Elektro')
            ->assertSee('Teknologi Informasi')
            ->assertSee('https://www.polinema.ac.id/jurusan/');

        $this->get(route('pages.show', 'tugas-dan-fungsi-utama'))
            ->assertOk()
            ->assertSee('Kantor Jaminan Mutu (KJM)')
            ->assertSee('Level I')
            ->assertSee('Level IV')
            ->assertSee('Pengendalian Matriks Kompetensi')
            ->assertSee('tidak menyatakan status sertifikasi saat ini');
    }
}
