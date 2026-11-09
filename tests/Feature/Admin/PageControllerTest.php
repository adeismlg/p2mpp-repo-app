<?php

namespace Tests\Feature\Admin;

use App\Models\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_can_update_both_localized_page_contents(): void
    {
        $page = Page::query()->create([
            'title' => 'Halaman uji',
            'title_en' => 'Test page',
            'slug' => 'halaman-uji',
            'content' => '<p>Konten lama</p>',
            'content_en' => '<p>Old content</p>',
            'is_published' => true,
        ]);
        $editor = User::factory()->create(['role' => User::ROLE_EDITOR]);

        $response = $this->actingAs($editor)->put(route('admin.pages.update', $page), [
            'title' => 'Halaman terbaru',
            'title_en' => 'Updated page',
            'content' => '<div class="page-content-panel page-content-panel--blue"><p>Konten baru</p></div>',
            'content_en' => '<div class="page-content-panel page-content-panel--amber"><p>New content</p></div>',
            'is_published' => '1',
        ]);

        $response->assertRedirect(route('admin.pages.index'));
        $this->assertDatabaseHas('pages', [
            'id' => $page->id,
            'title' => 'Halaman terbaru',
            'title_en' => 'Updated page',
            'content' => '<div class="page-content-panel page-content-panel--blue"><p>Konten baru</p></div>',
            'content_en' => '<div class="page-content-panel page-content-panel--amber"><p>New content</p></div>',
            'is_published' => true,
        ]);
    }

    public function test_page_editor_explains_the_visual_content_blocks(): void
    {
        $page = Page::query()->create([
            'title' => 'Halaman uji',
            'slug' => 'halaman-uji',
            'is_published' => true,
        ]);
        $editor = User::factory()->create(['role' => User::ROLE_EDITOR]);

        $this->actingAs($editor)
            ->get(route('admin.pages.edit', $page))
            ->assertSee('Gunakan menu Blok untuk panel dan tata letak', false)
            ->assertSee('data-rich-editor-blocks', false)
            ->assertSee('data-rich-editor-css=', false)
            ->assertSee('data-rich-editor-upload-url=', false);
    }

    public function test_dynamic_program_accreditation_page_points_editors_to_its_data_manager(): void
    {
        $page = Page::query()->create([
            'title' => 'Akreditasi Program Studi',
            'slug' => 'akreditasi-program-studi',
            'is_published' => true,
        ]);
        $editor = User::factory()->create(['role' => User::ROLE_EDITOR]);

        $this->actingAs($editor)
            ->get(route('admin.pages.edit', $page))
            ->assertSee('Kelola informasi dan sertifikat per program melalui menu Akreditasi Prodi.')
            ->assertDontSee('data-rich-editor', false);
    }

    public function test_public_page_renders_visual_content_blocks(): void
    {
        Page::query()->create([
            'title' => 'Halaman contoh',
            'slug' => 'halaman-contoh',
            'content' => '<div class="page-content-panel page-content-panel--blue"><h3>Panel contoh</h3><p>Konten contoh</p></div>',
            'is_published' => true,
        ]);

        $this->get(route('pages.show', 'halaman-contoh'))
            ->assertSee('page-content-panel page-content-panel--blue', false)
            ->assertSee('Konten contoh');
    }

    public function test_published_pages_use_the_same_unframed_content_layout(): void
    {
        $page = Page::query()->create([
            'title' => 'Kebijakan Mutu',
            'slug' => 'kebijakan-mutu',
            'content' => '<h2>Komitmen mutu</h2><p>Perbaikan berkelanjutan.</p>',
            'is_published' => true,
        ]);

        $this->get(route('pages.show', $page))
            ->assertSee('<h1 class="mb-7', false)
            ->assertSee('page-canvas', false)
            ->assertSee('<h2>Komitmen mutu</h2>', false)
            ->assertSee('Perbaikan berkelanjutan.')
            ->assertDontSee('rounded-xl border bg-white p-8 shadow-sm');
    }

    public function test_vision_and_mission_page_uses_the_same_unframed_content_layout(): void
    {
        $page = Page::query()->create([
            'title' => 'Visi dan Misi Polinema',
            'slug' => 'visi-misi-polinema',
            'content' => '<h2>Visi</h2><p>Isi visi</p>',
            'is_published' => true,
        ]);

        $this->get(route('pages.show', $page))
            ->assertSee('<h1 class="mb-7', false)
            ->assertSee('<h2>Visi</h2>', false)
            ->assertDontSee('bg-gradient-to-br')
            ->assertDontSee('Profil Polinema')
            ->assertDontSee('rounded-xl border bg-white p-8 shadow-sm');
    }
}
