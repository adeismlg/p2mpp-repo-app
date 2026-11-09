<?php

namespace App\Http\Controllers;

use App\Models\StudyProgramAccreditation;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AccreditationCertificateController extends Controller
{
    public function download(StudyProgramAccreditation $study_program_accreditation): StreamedResponse
    {
        abort_unless(
            StudyProgramAccreditation::query()
                ->latestCurrentlyValid()
                ->whereKey($study_program_accreditation->getKey())
                ->exists()
            && $study_program_accreditation->certificate_path
            && Storage::disk('local')->exists($study_program_accreditation->certificate_path),
            404,
        );

        return Storage::disk('local')->download(
            $study_program_accreditation->certificate_path,
            $this->downloadFilename(
                $study_program_accreditation->localized('name').' '.$study_program_accreditation->study_level,
                $study_program_accreditation->certificate_path,
            ),
        );
    }
}
