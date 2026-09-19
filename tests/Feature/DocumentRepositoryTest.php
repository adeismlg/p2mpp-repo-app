<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\DocumentCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('documents');
        Storage::fake('public');
    }

    public function test_public_document_list_shows_only_public_documents(): void
    {
        $category = DocumentCategory::factory()->create();

        Document::factory()->create([
            'document_category_id' => $category->id,
            'title' => 'Dokumen Publik',
            'is_public' => true,
        ]);

        Document::factory()->create([
            'document_category_id' => $category->id,
            'title' => 'Dokumen Tersembunyi',
            'is_public' => false,
        ]);

        $response = $this->get(route('documents.index'));

        $response->assertOk();
        $response->assertSee('Dokumen Publik');
        $response->assertDontSee('Dokumen Tersembunyi');
    }

    public function test_search_filters_documents_by_title(): void
    {
        Document::factory()->create(['title' => 'Panduan Akademik 2026', 'is_public' => true]);
        Document::factory()->create(['title' => 'Formulir Cuti', 'is_public' => true]);

        $response = $this->get(route('documents.index', ['q' => 'Akademik']));

        $response->assertOk();
        $response->assertSee('Panduan Akademik 2026');
        $response->assertDontSee('Formulir Cuti');
    }

    public function test_hidden_document_cannot_be_viewed_directly(): void
    {
        $document = Document::factory()->create(['is_public' => false]);

        $this->get(route('documents.show', $document))->assertNotFound();
        $this->get(route('documents.download', $document))->assertNotFound();
    }

    public function test_downloading_a_document_increments_its_counter(): void
    {
        $file = UploadedFile::fake()->create('contoh.pdf', 100);
        $path = $file->storeAs('/', 'contoh.pdf', 'documents');

        $document = Document::factory()->create([
            'is_public' => true,
            'file_path' => $path,
            'file_name' => 'contoh.pdf',
            'downloads_count' => 0,
        ]);

        $response = $this->get(route('documents.download', $document));

        $response->assertOk();
        $this->assertEquals(1, $document->fresh()->downloads_count);
    }
}
