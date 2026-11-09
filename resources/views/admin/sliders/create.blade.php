@extends('layouts.admin')

@section('title', 'Slider Baru')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Slider Baru</h1>

    <form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data" data-upload-progress-form class="bg-white border rounded-xl p-6 lg:p-8 max-w-3xl space-y-5">
        @csrf
        @include('admin.sliders.form')
        @include('admin._upload-progress')
        <button type="submit" class="bg-indigo-600 text-white text-sm font-semibold px-6 py-2.5 rounded-lg hover:bg-indigo-700">Simpan</button>
    </form>
@endsection
