@extends('layouts.admin')

@section('title', 'Edit Slider')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Edit Slider</h1>

    <form action="{{ route('admin.sliders.update', $slider) }}" method="POST" enctype="multipart/form-data" class="bg-white border rounded-xl p-6 lg:p-8 max-w-3xl space-y-5">
        @csrf
        @method('PUT')
        @include('admin.sliders.form')
        <button type="submit" class="bg-indigo-600 text-white text-sm font-semibold px-6 py-2.5 rounded-lg hover:bg-indigo-700">Simpan Perubahan</button>
    </form>
@endsection
