<form action="{{ $accreditation->exists ? route('admin.study-program-accreditations.update', $accreditation) : route('admin.study-program-accreditations.store') }}"
      method="POST" enctype="multipart/form-data" data-upload-progress-form class="max-w-3xl space-y-5 rounded-xl border bg-white p-6">
    @csrf
    @if ($accreditation->exists)
        @method('PUT')
    @endif

    @if ($accreditation->source_document_name)
        <div class="rounded-lg bg-slate-50 p-4 text-sm">
            <p class="font-medium text-slate-700">Nama dokumen pada CSV</p>
            <p class="mt-1 break-all text-slate-500">{{ $accreditation->source_document_name }}</p>
            <p class="mt-2 text-xs text-slate-500">File aslinya tidak tersedia pada CSV. Unggah file sertifikat dari sumber Anda.</p>
        </div>
    @endif

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="study_level" class="mb-1 block text-sm font-medium">Jenjang</label>
            <select id="study_level" name="study_level" required class="w-full rounded-lg border px-3 py-2 text-sm">
                @foreach (['D-II', 'D-III', 'D-IV', 'S2', 'S3'] as $level)
                    <option value="{{ $level }}" @selected(old('study_level', $accreditation->study_level ?? 'D-III') === $level)>{{ $level }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="rating" class="mb-1 block text-sm font-medium">Peringkat akreditasi</label>
            <input id="rating" name="rating" value="{{ old('rating', $accreditation->rating) }}" required class="w-full rounded-lg border px-3 py-2 text-sm">
        </div>
    </div>

    <div>
        <label for="name" class="mb-1 block text-sm font-medium">Nama program studi</label>
        <input id="name" name="name" value="{{ old('name', $accreditation->name) }}" required class="w-full rounded-lg border px-3 py-2 text-sm">
    </div>

    <div>
        <label for="name_en" class="mb-1 block text-sm font-medium">Nama program studi (English, opsional)</label>
        <input id="name_en" name="name_en" value="{{ old('name_en', $accreditation->name_en) }}" class="w-full rounded-lg border px-3 py-2 text-sm">
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="decree_number" class="mb-1 block text-sm font-medium">Nomor SK / sertifikat</label>
            <input id="decree_number" name="decree_number" value="{{ old('decree_number', $accreditation->decree_number) }}" required class="w-full rounded-lg border px-3 py-2 text-sm">
        </div>
        <div>
            <label for="valid_until" class="mb-1 block text-sm font-medium">Berlaku sampai</label>
            <input id="valid_until" type="date" name="valid_until" value="{{ old('valid_until', $accreditation->valid_until?->format('Y-m-d')) }}" required class="w-full rounded-lg border px-3 py-2 text-sm">
        </div>
    </div>

    <div>
        <label for="certificate" class="mb-1 block text-sm font-medium">File sertifikat (PDF, JPG, atau PNG; maks. 25 MB)</label>
        @if ($accreditation->certificate_path)
            <p class="mb-2 text-xs text-slate-500">File saat ini: {{ $accreditation->certificate_file_name }}. Pilih file baru jika ingin menggantinya.</p>
        @else
            <p class="mb-2 text-xs text-slate-500">Unggah sertifikat untuk menampilkan tautan unduhan pada halaman publik.</p>
        @endif
        <input id="certificate" type="file" name="certificate" accept=".pdf,.jpg,.jpeg,.png"
               @required(! $accreditation->exists) class="w-full rounded-lg border px-3 py-2 text-sm">
    </div>

    @include('admin._upload-progress')
    <div class="flex flex-wrap gap-3">
        <button type="submit" class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">Simpan</button>
        <a href="{{ route('admin.study-program-accreditations.index') }}" class="rounded-lg border px-5 py-2.5 text-sm font-semibold hover:bg-slate-50">Batal</a>
    </div>
</form>
