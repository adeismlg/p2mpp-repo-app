<form action="{{ $evidence->exists ? route('admin.accreditation-evidences.update', $evidence) : route('admin.accreditation-evidences.store') }}"
      method="POST" enctype="multipart/form-data" data-upload-progress-form class="max-w-3xl space-y-5 rounded-xl border bg-white p-6">
    @csrf
    @if ($evidence->exists)
        @method('PUT')
    @endif

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="instrument" class="mb-1 block text-sm font-medium">Instrumen / standar</label>
            <input id="instrument" name="instrument" value="{{ old('instrument', $evidence->instrument) }}" class="w-full rounded-lg border px-3 py-2 text-sm">
        </div>
        <div>
            <label for="scope" class="mb-1 block text-sm font-medium">Lingkup</label>
            <select id="scope" name="scope" required class="w-full rounded-lg border px-3 py-2 text-sm">
                @foreach (\App\Models\AccreditationEvidence::SCOPES as $value => $label)
                    <option value="{{ $value }}" @selected(old('scope', $evidence->scope ?? 'institution') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="assessment_entity" class="mb-1 block text-sm font-medium">Program studi / unit (opsional)</label>
            <input id="assessment_entity" name="assessment_entity" value="{{ old('assessment_entity', $evidence->assessment_entity) }}" class="w-full rounded-lg border px-3 py-2 text-sm">
        </div>
        <div>
            <label for="criterion_code" class="mb-1 block text-sm font-medium">Kode kriteria</label>
            <input id="criterion_code" name="criterion_code" value="{{ old('criterion_code', $evidence->criterion_code) }}" required class="w-full rounded-lg border px-3 py-2 text-sm">
        </div>
    </div>

    <div>
        <label for="criterion_name" class="mb-1 block text-sm font-medium">Nama / uraian kriteria</label>
        <textarea id="criterion_name" name="criterion_name" rows="3" required class="w-full rounded-lg border px-3 py-2 text-sm">{{ old('criterion_name', $evidence->criterion_name) }}</textarea>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="evidence_title" class="mb-1 block text-sm font-medium">Nama bukti yang dibutuhkan</label>
            <input id="evidence_title" name="evidence_title" value="{{ old('evidence_title', $evidence->evidence_title) }}" required class="w-full rounded-lg border px-3 py-2 text-sm">
        </div>
        <div>
            <label for="owner_id" class="mb-1 block text-sm font-medium">Penanggung jawab</label>
            <select id="owner_id" name="owner_id" class="w-full rounded-lg border px-3 py-2 text-sm">
                <option value="">Belum ditentukan</option>
                @foreach ($staffUsers as $staffUser)
                    <option value="{{ $staffUser->id }}" @selected((string) old('owner_id', $evidence->owner_id) === (string) $staffUser->id)>{{ $staffUser->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="status" class="mb-1 block text-sm font-medium">Status</label>
            <select id="status" name="status" required class="w-full rounded-lg border px-3 py-2 text-sm">
                @foreach (\App\Models\AccreditationEvidence::STATUSES as $value => $label)
                    <option value="{{ $value }}" @selected(old('status', $evidence->status ?? 'draft') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="due_date" class="mb-1 block text-sm font-medium">Tenggat</label>
            <input id="due_date" type="date" name="due_date" value="{{ old('due_date', $evidence->due_date?->format('Y-m-d')) }}" class="w-full rounded-lg border px-3 py-2 text-sm">
        </div>
    </div>

    <div>
        <label for="evidence_file" class="mb-1 block text-sm font-medium">Berkas bukti (PDF, Office, atau ZIP; maks. 25 MB)</label>
        @if ($evidence->evidence_path)
            <p class="mb-2 text-xs text-slate-500">Berkas telah diunggah. Pilih file baru jika ingin menggantinya.</p>
        @endif
        <input id="evidence_file" type="file" name="evidence_file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip"
               @required(! $evidence->exists) class="w-full rounded-lg border px-3 py-2 text-sm">
    </div>

    <div>
        <label for="notes" class="mb-1 block text-sm font-medium">Catatan / tindak lanjut</label>
        <textarea id="notes" name="notes" rows="3" class="w-full rounded-lg border px-3 py-2 text-sm">{{ old('notes', $evidence->notes) }}</textarea>
    </div>

    @include('admin._upload-progress')
    <div class="flex flex-wrap gap-3">
        <button type="submit" class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">Simpan</button>
        <a href="{{ route('admin.accreditation-evidences.index') }}" class="rounded-lg border px-5 py-2.5 text-sm font-semibold hover:bg-slate-50">Batal</a>
    </div>
</form>
