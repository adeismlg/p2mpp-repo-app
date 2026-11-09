<?php

namespace Tests\Feature\Admin;

use App\Models\News;
use App\Models\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EditorImageControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_can_upload_an_image_for_rich_content(): void
    {
        Storage::fake('public');
        $editor = User::factory()->create(['role' => User::ROLE_EDITOR]);

        $response = $this->actingAs($editor)->postJson(route('admin.editor-images.store'), [
            'file' => UploadedFile::fake()->image('content.png'),
        ]);

        $response->assertOk()
            ->assertJsonStructure(['location']);
        $this->assertStringStartsWith('/storage/editor-images/', $response->json('location'));

        $this->assertCount(1, Storage::disk('public')->allFiles('editor-images'));
    }

    public function test_editor_image_upload_rejects_non_image_files(): void
    {
        Storage::fake('public');
        $editor = User::factory()->create(['role' => User::ROLE_EDITOR]);

        $response = $this->actingAs($editor)->postJson(route('admin.editor-images.store'), [
            'file' => UploadedFile::fake()->create('payload.svg', 1, 'image/svg+xml'),
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors('file');
        $this->assertSame([], Storage::disk('public')->allFiles());
    }

    public function test_editor_image_upload_rejects_files_over_25_megabytes(): void
    {
        Storage::fake('public');
        $editor = User::factory()->create(['role' => User::ROLE_EDITOR]);
        $image = UploadedFile::fake()->image('large.png')->size(25_601);

        $response = $this->actingAs($editor)->postJson(route('admin.editor-images.store'), [
            'file' => $image,
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors('file');
        $this->assertSame([], Storage::disk('public')->allFiles());
    }

    public function test_editor_image_upload_rejects_images_with_excessive_dimensions(): void
    {
        Storage::fake('public');
        $editor = User::factory()->create(['role' => User::ROLE_EDITOR]);
        $image = UploadedFile::fake()->image('large.png', 1, 1);
        $contents = substr_replace($image->get(), pack('N', 10001), 16, 4);
        file_put_contents($image->getPathname(), $contents);

        $response = $this->actingAs($editor)->postJson(route('admin.editor-images.store'), [
            'file' => $image,
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors('file');
        $this->assertSame([], Storage::disk('public')->allFiles());
    }

    public function test_guest_and_non_staff_cannot_upload_editor_images(): void
    {
        Storage::fake('public');
        $this->postJson(route('admin.editor-images.store'), [
            'file' => UploadedFile::fake()->image('guest.png'),
        ])
            ->assertUnauthorized();

        $guest = User::factory()->create(['role' => User::ROLE_GUEST]);

        $this->actingAs($guest)
            ->postJson(route('admin.editor-images.store'), [
                'file' => UploadedFile::fake()->image('guest.png'),
            ])
            ->assertForbidden();

        $this->assertSame([], Storage::disk('public')->allFiles());
    }

    public function test_page_and_news_editors_expose_the_image_upload_feature(): void
    {
        $editor = User::factory()->create(['role' => User::ROLE_EDITOR]);
        $page = Page::query()->create([
            'title' => 'Halaman uji',
            'slug' => 'halaman-uji',
            'is_published' => true,
        ]);
        $news = News::query()->create([
            'title' => 'Berita uji',
            'content' => 'Konten berita',
        ]);

        $this->actingAs($editor)
            ->get(route('admin.pages.edit', $page))
            ->assertOk()
            ->assertSee('data-rich-editor-upload-url="'.route('admin.editor-images.store').'"', false)
            ->assertSee('data-rich-editor-csrf=', false)
            ->assertSee('maksimal 25 MB');

        $this->get(route('admin.news.edit', $news))
            ->assertOk()
            ->assertSee('data-rich-editor-upload-url="'.route('admin.editor-images.store').'"', false)
            ->assertSee('data-rich-editor-csrf=', false)
            ->assertSee('maksimal 25 MB');
    }
}
