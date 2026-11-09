<?php

namespace Tests\Feature;

use App\Models\Page;
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
            ->assertSee('2314/SK/BAN-PT/Ak/PT/VI/2025')
            ->assertSee('4 Juni 2025 sampai dengan 4 Juni 2030')
            ->assertDontSee('Sumber:')
            ->assertDontSee('slot gacor');

        $this->get(route('pages.show', 'akreditasi-program-studi'))
            ->assertOk()
            ->assertSee('0449/SK/LAM Teknik/VD3/VIII/2025')
            ->assertSee('Teknik Sipil')
            ->assertSee('Unggul')
            ->assertSee('20 Aug 2030')
            ->assertDontSee('PDDikti')
            ->assertDontSee('Sumber:');

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
            ->assertDontSee('Sumber:')
            ->assertDontSee('slot gacor');

        $this->get(route('pages.show', 'jurusan-polinema'))
            ->assertOk()
            ->assertSee('9 Februari 1982')
            ->assertSee('46 program studi')
            ->assertSee('11 program studi D-III')
            ->assertSee('Pamekasan')
            ->assertSee('Teknik Elektro')
            ->assertSee('Teknologi Informasi')
            ->assertDontSee('Sumber:');

        $this->get(route('pages.show', 'tugas-dan-fungsi-utama'))
            ->assertOk()
            ->assertSee('Kantor Jaminan Mutu (KJM)')
            ->assertSee('Level I')
            ->assertSee('Level IV')
            ->assertSee('Pengendalian Matriks Kompetensi')
            ->assertSee('tidak menyatakan status sertifikasi saat ini');
    }

    public function test_seeded_pages_do_not_include_source_attribution_footers(): void
    {
        $this->seed();

        foreach (Page::query()->get() as $page) {
            $this->assertStringNotContainsString('Sumber:', $page->content ?? '');
            $this->assertStringNotContainsString('Source:', $page->content_en ?? '');
        }
    }

    public function test_program_accreditation_page_lists_only_currently_valid_csv_records(): void
    {
        $this->travelTo('2026-11-09 00:00:00');
        $this->seed();

        $response = $this->get(route('pages.show', 'akreditasi-program-studi'));

        $this->assertSame(41, substr_count($response->getContent(), '<tr>') - 1);
        $response->assertDontSee('980/SK/BAN-PT/Akred/Dipl-III/II/2021');
        $response->assertDontSee('5474/SK/BAN-PT/Ak/MTr/VIII/2022');
    }
}
