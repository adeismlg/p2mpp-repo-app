<?php

namespace Tests\Feature;

use App\Models\Document;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentDownloadTest extends TestCase
{
    use RefreshDatabase;

    public function test_official_p2mpp_document_redirects_to_its_source(): void
    {
        $source = 'https://p2mpp.polinema.ac.id/assets/uploads/files/SPMI_2021.pdf';
        $document = Document::create([
            'title' => 'SPMI 2021',
            'file_path' => $source,
            'file_name' => 'SPMI_2021.pdf',
            'file_type' => 'application/pdf',
            'is_public' => true,
        ]);

        $this->get(route('documents.download', $document))
            ->assertRedirect($source);

        $this->assertDatabaseHas('documents', [
            'id' => $document->id,
            'downloads_count' => 1,
        ]);
    }

    public function test_other_external_document_urls_are_not_redirected(): void
    {
        Storage::fake('documents');
        $document = Document::create([
            'title' => 'External Document',
            'file_path' => 'https://example.com/document.pdf',
            'file_name' => 'document.pdf',
            'file_type' => 'application/pdf',
            'is_public' => true,
        ]);

        $this->get(route('documents.download', $document))
            ->assertNotFound();

        $this->assertDatabaseHas('documents', [
            'id' => $document->id,
            'downloads_count' => 0,
        ]);
    }

    public function test_stored_document_downloads_still_use_local_storage(): void
    {
        Storage::fake('documents');
        Storage::disk('documents')->put('guide.pdf', 'Document contents');
        $document = Document::create([
            'title' => 'Guide',
            'file_path' => 'guide.pdf',
            'file_name' => 'guide.pdf',
            'file_type' => 'application/pdf',
            'is_public' => true,
        ]);

        $this->get(route('documents.download', $document))
            ->assertDownload('guide.pdf');

        $this->assertDatabaseHas('documents', [
            'id' => $document->id,
            'downloads_count' => 1,
        ]);
    }
}
