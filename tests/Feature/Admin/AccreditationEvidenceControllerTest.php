<?php

namespace Tests\Feature\Admin;

use App\Models\AccreditationEvidence;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AccreditationEvidenceControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_can_view_the_evidence_workflow_and_dashboard_summary(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $this->actingAs($admin);

        $this->get(route('admin.accreditation-evidences.index'))
            ->assertOk()
            ->assertSee('Matriks Bukti Akreditasi')
            ->assertSee('Belum ada bukti akreditasi.');

        $this->get(route('admin.accreditation-evidences.create'))
            ->assertOk()
            ->assertSee('Nama / uraian kriteria');

        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Kesiapan Bukti Akreditasi');
    }

    public function test_staff_can_create_evidence_and_store_its_file_privately(): void
    {
        Storage::fake('local');
        $owner = User::factory()->create(['role' => User::ROLE_EDITOR]);
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $response = $this->actingAs($admin)->post(route('admin.accreditation-evidences.store'), [
            'instrument' => 'Instrumen uji',
            'scope' => 'program',
            'assessment_entity' => 'Teknik Elektro',
            'criterion_code' => 'C.1',
            'criterion_name' => 'Visi, misi, tujuan, dan strategi',
            'evidence_title' => 'Dokumen rencana strategis',
            'owner_id' => $owner->id,
            'status' => 'in_progress',
            'due_date' => '2027-01-31',
            'notes' => 'Koordinasikan dengan unit terkait.',
            'evidence_file' => UploadedFile::fake()->create('rencana.pdf', 20, 'application/pdf'),
        ]);

        $response->assertRedirect(route('admin.accreditation-evidences.index'));
        $this->assertDatabaseHas('accreditation_evidence', [
            'criterion_code' => 'C.1',
            'owner_id' => $owner->id,
            'status' => 'in_progress',
            'scope' => 'program',
        ]);

        $evidence = AccreditationEvidence::query()->where('criterion_code', 'C.1')->firstOrFail();
        Storage::disk('local')->assertExists($evidence->evidence_path);
        $this->assertStringNotContainsString('rencana.pdf', basename($evidence->evidence_path));
    }

    public function test_guest_is_redirected_from_the_internal_evidence_register(): void
    {
        $this->get(route('admin.accreditation-evidences.index'))
            ->assertRedirect(route('login'));
    }

    public function test_non_staff_cannot_download_private_evidence(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('accreditation-evidences/private.pdf', 'Private evidence');
        $evidence = AccreditationEvidence::factory()->create([
            'evidence_path' => 'accreditation-evidences/private.pdf',
        ]);
        $guest = User::factory()->create(['role' => User::ROLE_GUEST]);

        $this->actingAs($guest)
            ->get(route('admin.accreditation-evidences.download', $evidence))
            ->assertForbidden();
    }

    public function test_staff_can_download_private_evidence(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('accreditation-evidences/evidence.pdf', 'Private evidence');
        $evidence = AccreditationEvidence::factory()->create([
            'evidence_path' => 'accreditation-evidences/evidence.pdf',
            'evidence_title' => 'Bukti Visi Misi C.1',
        ]);
        $editor = User::factory()->create(['role' => User::ROLE_EDITOR]);

        $this->actingAs($editor)
            ->get(route('admin.accreditation-evidences.download', $evidence))
            ->assertDownload('bukti-visi-misi-c1.pdf');
    }

    public function test_invalid_evidence_file_is_rejected_without_creating_a_record(): void
    {
        Storage::fake('local');
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $response = $this->actingAs($admin)->from(route('admin.accreditation-evidences.create'))
            ->post(route('admin.accreditation-evidences.store'), [
                'scope' => 'institution',
                'criterion_code' => 'C.2',
                'criterion_name' => 'Tata pamong',
                'evidence_title' => 'Dokumen kebijakan',
                'status' => 'draft',
                'evidence_file' => UploadedFile::fake()->create('payload.exe', 10, 'application/x-msdownload'),
            ]);

        $response->assertSessionHasErrors('evidence_file');
        $this->assertDatabaseCount('accreditation_evidence', 0);
        $this->assertSame([], Storage::disk('local')->allFiles());
    }

    public function test_staff_can_replace_and_delete_an_evidence_file(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('accreditation-evidences/old.pdf', 'Old evidence');
        $evidence = AccreditationEvidence::factory()->create([
            'evidence_path' => 'accreditation-evidences/old.pdf',
        ]);
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $response = $this->actingAs($admin)->put(route('admin.accreditation-evidences.update', $evidence), [
            'scope' => 'institution',
            'criterion_code' => $evidence->criterion_code,
            'criterion_name' => $evidence->criterion_name,
            'evidence_title' => 'Dokumen terbaru',
            'status' => 'complete',
            'evidence_file' => UploadedFile::fake()->create('new.pdf', 20, 'application/pdf'),
        ]);

        $response->assertRedirect(route('admin.accreditation-evidences.index'));
        $evidence->refresh();
        $this->assertSame('complete', $evidence->status);
        Storage::disk('local')->assertMissing('accreditation-evidences/old.pdf');
        Storage::disk('local')->assertExists($evidence->evidence_path);

        $this->delete(route('admin.accreditation-evidences.destroy', $evidence))
            ->assertRedirect();

        $this->assertDatabaseMissing('accreditation_evidence', ['id' => $evidence->id]);
        Storage::disk('local')->assertMissing($evidence->evidence_path);
    }
}
