<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccreditationEvidence;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AccreditationEvidenceController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', Rule::in(array_keys(AccreditationEvidence::STATUSES))],
        ]);

        $evidences = AccreditationEvidence::query()
            ->with('owner')
            ->when($filters['q'] ?? null, function ($query, string $term): void {
                $query->where(function ($query) use ($term): void {
                    $query->where('criterion_code', 'like', "%{$term}%")
                        ->orWhere('criterion_name', 'like', "%{$term}%")
                        ->orWhere('evidence_title', 'like', "%{$term}%")
                        ->orWhere('assessment_entity', 'like', "%{$term}%");
                });
            })
            ->when($filters['status'] ?? null, fn ($query, string $status) => $query->where('status', $status))
            ->orderByRaw('CASE WHEN due_date IS NULL THEN 1 ELSE 0 END')
            ->orderBy('due_date')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.accreditation-evidences.index', [
            'evidences' => $evidences,
            'filters' => $filters,
        ]);
    }

    public function create(): View
    {
        return view('admin.accreditation-evidences.create', [
            'evidence' => new AccreditationEvidence,
            'staffUsers' => $this->staffUsers(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request, true);
        $file = $request->file('evidence_file');
        $path = $file->store('accreditation-evidences', 'local');

        if ($path === false) {
            throw new RuntimeException('Berkas bukti akreditasi gagal disimpan.');
        }

        AccreditationEvidence::create([
            ...$data,
            'evidence_path' => $path,
        ]);

        return redirect()->route('admin.accreditation-evidences.index')
            ->with('status', 'Bukti akreditasi berhasil ditambahkan.');
    }

    public function edit(AccreditationEvidence $accreditation_evidence): View
    {
        return view('admin.accreditation-evidences.edit', [
            'evidence' => $accreditation_evidence,
            'staffUsers' => $this->staffUsers(),
        ]);
    }

    public function update(Request $request, AccreditationEvidence $accreditation_evidence): RedirectResponse
    {
        $data = $this->validatedData($request, false);
        $oldPath = $accreditation_evidence->evidence_path;

        if ($request->hasFile('evidence_file')) {
            $path = $request->file('evidence_file')->store('accreditation-evidences', 'local');

            if ($path === false) {
                throw new RuntimeException('Berkas bukti akreditasi gagal disimpan.');
            }

            $data['evidence_path'] = $path;
        }

        $accreditation_evidence->update($data);

        if (isset($data['evidence_path'])) {
            $this->deleteEvidenceFile($oldPath);
        }

        return redirect()->route('admin.accreditation-evidences.index')
            ->with('status', 'Bukti akreditasi berhasil diperbarui.');
    }

    public function destroy(AccreditationEvidence $accreditation_evidence): RedirectResponse
    {
        $path = $accreditation_evidence->evidence_path;
        $accreditation_evidence->delete();

        $this->deleteEvidenceFile($path);

        return back()->with('status', 'Bukti akreditasi berhasil dihapus.');
    }

    public function download(AccreditationEvidence $accreditation_evidence): StreamedResponse
    {
        abort_unless(
            $accreditation_evidence->evidence_path
            && Storage::disk('local')->exists($accreditation_evidence->evidence_path),
            404,
        );

        return Storage::disk('local')->download($accreditation_evidence->evidence_path);
    }

    private function validatedData(Request $request, bool $fileRequired): array
    {
        $data = $request->validate([
            'instrument' => ['nullable', 'string', 'max:255'],
            'scope' => ['required', Rule::in(array_keys(AccreditationEvidence::SCOPES))],
            'assessment_entity' => ['nullable', 'string', 'max:255'],
            'criterion_code' => ['required', 'string', 'max:80'],
            'criterion_name' => ['required', 'string', 'max:2000'],
            'evidence_title' => ['required', 'string', 'max:255'],
            'owner_id' => [
                'nullable',
                Rule::exists('users', 'id')->whereIn('role', [User::ROLE_ADMIN, User::ROLE_EDITOR]),
            ],
            'status' => ['required', Rule::in(array_keys(AccreditationEvidence::STATUSES))],
            'due_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'evidence_file' => [
                $fileRequired ? 'required' : 'nullable',
                'file',
                'max:20480',
                'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip',
            ],
        ]);

        unset($data['evidence_file']);

        return $data;
    }

    private function staffUsers(): Collection
    {
        return User::query()
            ->whereIn('role', [User::ROLE_ADMIN, User::ROLE_EDITOR])
            ->orderBy('name')
            ->get();
    }

    private function deleteEvidenceFile(?string $path): void
    {
        $disk = Storage::disk('local');

        if ($path && $disk->exists($path) && ! $disk->delete($path)) {
            throw new RuntimeException('Berkas bukti akreditasi gagal dihapus.');
        }
    }
}
