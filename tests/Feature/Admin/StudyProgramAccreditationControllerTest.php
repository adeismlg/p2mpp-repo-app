<?php

namespace Tests\Feature\Admin;

use App\Models\StudyProgramAccreditation;
use App\Models\User;
use Database\Seeders\StudyProgramAccreditationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StudyProgramAccreditationControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_can_manage_individual_program_accreditations_and_certificates(): void
    {
        Storage::fake('local');
        $staff = User::factory()->create(['role' => User::ROLE_EDITOR]);

        $response = $this->actingAs($staff)->post(route('admin.study-program-accreditations.store'), [
            'study_level' => 'D-III',
            'decree_number' => '0449/SK/LAM Teknik/VD3/VIII/2025',
            'name' => 'Teknik Sipil',
            'name_en' => 'Civil Engineering',
            'rating' => 'Unggul',
            'valid_until' => '2030-08-20',
            'certificate' => UploadedFile::fake()->create('sertifikat-teknik-sipil.pdf', 100, 'application/pdf'),
        ]);

        $response->assertRedirect(route('admin.study-program-accreditations.index'));
        $this->assertDatabaseHas('study_program_accreditations', [
            'name' => 'Teknik Sipil',
            'rating' => 'Unggul',
            'certificate_file_name' => 'sertifikat-teknik-sipil.pdf',
        ]);

        $accreditation = StudyProgramAccreditation::query()->where('name', 'Teknik Sipil')->firstOrFail();
        $this->assertSame('2030-08-20', $accreditation->valid_until->toDateString());
        Storage::disk('local')->assertExists($accreditation->certificate_path);
        $this->assertStringNotContainsString('sertifikat-teknik-sipil.pdf', basename($accreditation->certificate_path));

        $this->actingAs($staff)
            ->get(route('admin.study-program-accreditations.edit', $accreditation))
            ->assertOk()
            ->assertSee('sertifikat-teknik-sipil.pdf');
    }

    public function test_new_program_accreditation_requires_a_certificate_file(): void
    {
        Storage::fake('local');
        $staff = User::factory()->create(['role' => User::ROLE_EDITOR]);

        $response = $this->actingAs($staff)
            ->from(route('admin.study-program-accreditations.create'))
            ->post(route('admin.study-program-accreditations.store'), [
                'study_level' => 'D-IV',
                'decree_number' => 'SK-TEST',
                'name' => 'Program Uji',
                'rating' => 'Baik',
                'valid_until' => '2030-01-01',
            ]);

        $response->assertSessionHasErrors('certificate');
        $this->assertDatabaseCount('study_program_accreditations', 0);
        $this->assertSame([], Storage::disk('local')->allFiles());
    }

    public function test_guest_and_non_staff_cannot_manage_program_accreditations(): void
    {
        $this->get(route('admin.study-program-accreditations.index'))
            ->assertRedirect(route('login'));

        $guest = User::factory()->create(['role' => User::ROLE_GUEST]);
        $this->actingAs($guest)
            ->get(route('admin.study-program-accreditations.index'))
            ->assertForbidden();
    }

    public function test_staff_can_upload_a_certificate_to_an_imported_csv_record(): void
    {
        Storage::fake('local');
        $this->seed(StudyProgramAccreditationSeeder::class);
        $this->assertDatabaseCount('study_program_accreditations', 68);
        $staff = User::factory()->create(['role' => User::ROLE_EDITOR]);
        $accreditation = StudyProgramAccreditation::query()->where('source_id', '5712')->firstOrFail();
        $this->assertNull($accreditation->certificate_path);

        $response = $this->actingAs($staff)->put(route('admin.study-program-accreditations.update', $accreditation), [
            'study_level' => $accreditation->study_level,
            'decree_number' => $accreditation->decree_number,
            'name' => $accreditation->name,
            'name_en' => $accreditation->name_en,
            'rating' => $accreditation->rating,
            'valid_until' => $accreditation->valid_until->format('Y-m-d'),
            'certificate' => UploadedFile::fake()->create('sertifikat-baru.pdf', 100, 'application/pdf'),
        ]);

        $response->assertRedirect(route('admin.study-program-accreditations.index'));
        $accreditation->refresh();
        $this->assertSame('5712', $accreditation->source_id);
        $this->assertSame('sertifikat-baru.pdf', $accreditation->certificate_file_name);
        Storage::disk('local')->assertExists($accreditation->certificate_path);
        $this->assertStringNotContainsString('sertifikat-baru.pdf', basename($accreditation->certificate_path));
    }

    public function test_staff_downloads_certificate_using_the_program_name_instead_of_uploaded_filename(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('study-program-accreditations/random-name.pdf', 'Certificate');
        $staff = User::factory()->create(['role' => User::ROLE_EDITOR]);
        $accreditation = StudyProgramAccreditation::factory()->create([
            'name' => 'Teknik Sipil',
            'study_level' => 'D-III',
            'certificate_path' => 'study-program-accreditations/random-name.pdf',
            'certificate_file_name' => 'nama-asli.pdf',
        ]);

        $this->actingAs($staff)
            ->get(route('admin.study-program-accreditations.download', $accreditation))
            ->assertDownload('teknik-sipil-d-iii.pdf');
    }

    public function test_invalid_certificate_is_rejected_without_creating_an_accreditation(): void
    {
        Storage::fake('local');
        $staff = User::factory()->create(['role' => User::ROLE_EDITOR]);

        $response = $this->actingAs($staff)
            ->from(route('admin.study-program-accreditations.create'))
            ->post(route('admin.study-program-accreditations.store'), [
                'study_level' => 'D-III',
                'decree_number' => 'SK-TEST',
                'name' => 'Program Uji',
                'rating' => 'Baik',
                'valid_until' => '2030-01-01',
                'certificate' => UploadedFile::fake()->create('file.exe', 10, 'application/x-msdownload'),
            ]);

        $response->assertSessionHasErrors('certificate');
        $this->assertDatabaseCount('study_program_accreditations', 0);
        $this->assertSame([], Storage::disk('local')->allFiles());
    }
}
