<?php

namespace Tests\Feature;

use App\Models\StudyProgramAccreditation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AccreditationCertificateControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_current_program_certificate_is_available_to_public_visitors(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('study-program-accreditations/active.pdf', 'Certificate');
        $accreditation = StudyProgramAccreditation::factory()->create([
            'name' => 'Teknik Sipil',
            'study_level' => 'D-III',
            'valid_until' => today()->addYear(),
            'certificate_path' => 'study-program-accreditations/active.pdf',
            'certificate_file_name' => 'nama-asli-sertifikat.pdf',
        ]);

        $this->get(route('accreditations.study-program.download', $accreditation))
            ->assertDownload('teknik-sipil-d-iii.pdf');
    }

    public function test_expired_program_certificate_is_not_available_publicly(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('study-program-accreditations/expired.pdf', 'Certificate');
        $accreditation = StudyProgramAccreditation::factory()->create([
            'valid_until' => today()->subDay(),
            'certificate_path' => 'study-program-accreditations/expired.pdf',
            'certificate_file_name' => 'sertifikat-lama.pdf',
        ]);

        $this->get(route('accreditations.study-program.download', $accreditation))
            ->assertNotFound();
    }
}
