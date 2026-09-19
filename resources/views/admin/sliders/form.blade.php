<div>
    <label class="block text-sm font-medium mb-1">Judul</label>
    <input type="text" name="title" value="{{ old('title', $slider->title ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm" required>
</div>
<div>
    <label class="block text-sm font-medium mb-1">Judul (English)</label>
    <input type="text" name="title_en" value="{{ old('title_en', $slider->title_en ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
</div>
<div>
    <label class="block text-sm font-medium mb-1">Deskripsi</label>
    <textarea name="subtitle" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('subtitle', $slider->subtitle ?? '') }}</textarea>
</div>
<div>
    <label class="block text-sm font-medium mb-1">Deskripsi (English)</label>
    <textarea name="subtitle_en" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('subtitle_en', $slider->subtitle_en ?? '') }}</textarea>
</div>
<div>
    @if (!empty($slider->image ?? null))
        <img src="{{ asset('storage/'.$slider->image) }}" alt="" class="h-24 w-40 rounded-lg object-cover mb-2">
    @endif
    <label class="block text-sm font-medium mb-1">{{ !empty($slider->image ?? null) ? 'Ganti gambar' : 'Gambar' }}</label>
    <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="w-full border rounded-lg px-3 py-2 text-sm" @required(empty($slider->image ?? null))>
    <p class="text-xs text-slate-400 mt-1">Format JPG, PNG, atau WebP. Maksimal 4 MB.</p>
</div>
<div>
    <label class="block text-sm font-medium mb-1">Tautan tombol (opsional)</label>
    <input type="url" name="link_url" value="{{ old('link_url', $slider->link_url ?? '') }}" placeholder="https://..." class="w-full border rounded-lg px-3 py-2 text-sm">
</div>
<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <label class="block text-sm font-medium mb-1">Label tombol</label>
        <input type="text" name="button_label" value="{{ old('button_label', $slider->button_label ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Label tombol (English)</label>
        <input type="text" name="button_label_en" value="{{ old('button_label_en', $slider->button_label_en ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
    </div>
</div>
<div>
    <label class="block text-sm font-medium mb-1">Urutan tampil</label>
    <input type="number" name="order" min="0" value="{{ old('order', $slider->order ?? 0) }}" class="w-full border rounded-lg px-3 py-2 text-sm" required>
</div>
<div class="flex items-center gap-2">
    <input type="checkbox" name="is_active" id="is_active" value="1" @checked(old('is_active', $slider->is_active ?? true)) class="rounded">
    <label for="is_active" class="text-sm">Tampilkan di halaman beranda</label>
</div>
