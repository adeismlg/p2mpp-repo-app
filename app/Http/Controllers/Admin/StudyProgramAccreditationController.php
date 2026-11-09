<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudyProgramAccreditation;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StudyProgramAccreditationController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:255'],
        ]);

        $accreditations = StudyProgramAccreditation::query()
            ->when($filters['q'] ?? null, function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('decree_number', 'like', "%{$search}%")
                        ->orWhere('study_level', 'like', "%{$search}%");
                });
            })
            ->orderBy('study_level')
            ->orderBy('name')
            ->orderByDesc('valid_until')
            ->paginate(20)
            ->withQueryString();

        return view('admin.study-program-accreditations.index', compact('accreditations', 'filters'));
    }

    public function create(): View
    {
        return view('admin.study-program-accreditations.create', [
            'accreditation' => new StudyProgramAccreditation,
        ]);
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $data = $this->validated($request, true);
        $file = $request->file('certificate');
        $path = $file->store('study-program-accreditations', 'local');

        if ($path === false) {
            throw new RuntimeException('Sertifikat akreditasi gagal disimpan.');
        }

        unset($data['certificate']);

        StudyProgramAccreditation::create([
            ...$data,
            'certificate_path' => $path,
            'certificate_file_name' => $file->getClientOriginalName(),
        ]);

        return $this->respondAfterUpload($request, 'admin.study-program-accreditations.index', 'Akreditasi program studi berhasil ditambahkan.');
    }

    public function edit(StudyProgramAccreditation $study_program_accreditation): View
    {
        return view('admin.study-program-accreditations.edit', [
            'accreditation' => $study_program_accreditation,
        ]);
    }

    public function update(Request $request, StudyProgramAccreditation $study_program_accreditation): JsonResponse|RedirectResponse
    {
        $data = $this->validated($request);
        $oldPath = $study_program_accreditation->certificate_path;

        if ($request->hasFile('certificate')) {
            $file = $request->file('certificate');
            $path = $file->store('study-program-accreditations', 'local');

            if ($path === false) {
                throw new RuntimeException('Sertifikat akreditasi gagal disimpan.');
            }

            $data['certificate_path'] = $path;
            $data['certificate_file_name'] = $file->getClientOriginalName();
        }

        unset($data['certificate']);
        $study_program_accreditation->update($data);

        if (isset($data['certificate_path']) && $oldPath) {
            Storage::disk('local')->delete($oldPath);
        }

        return $this->respondAfterUpload($request, 'admin.study-program-accreditations.index', 'Akreditasi program studi berhasil diperbarui.');
    }

    public function destroy(StudyProgramAccreditation $study_program_accreditation): RedirectResponse
    {
        $path = $study_program_accreditation->certificate_path;
        $study_program_accreditation->delete();

        if ($path) {
            Storage::disk('local')->delete($path);
        }

        return back()->with('status', 'Data akreditasi program studi berhasil dihapus.');
    }

    public function download(StudyProgramAccreditation $study_program_accreditation): StreamedResponse
    {
        abort_unless(
            $study_program_accreditation->certificate_path
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

    private function validated(Request $request, bool $certificateRequired = false): array
    {
        return $request->validate([
            'study_level' => ['required', 'string', 'max:32'],
            'decree_number' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'name_en' => ['nullable', 'string', 'max:255'],
            'rating' => ['required', 'string', 'max:80'],
            'valid_until' => ['required', 'date'],
            'certificate' => [
                $certificateRequired ? 'required' : 'nullable',
                'file',
                'max:'.config('uploads.max_size_kilobytes'),
                'mimes:pdf,jpg,jpeg,png',
            ],
        ]);
    }
}
