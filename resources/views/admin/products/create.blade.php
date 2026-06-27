{{-- ============================================================ --}}
{{-- resources/views/admin/products/create.blade.php --}}
{{-- ============================================================ --}}
@extends('layouts.admin')
@section('title', 'Tambah Produk')

@section('content')
<div class="max-w-2xl">
    <a href="{{ route('admin.products.index') }}" class="text-sm text-indigo-500 hover:underline mb-4 block">
        <i class="ti ti-arrow-left"></i> Kembali
    </a>

    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @include('admin.products._form')
            <div class="pt-2">
                <button class="bg-indigo-600 text-white px-6 py-2.5 rounded-lg hover:bg-indigo-700 font-medium">
                    Simpan Produk
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
