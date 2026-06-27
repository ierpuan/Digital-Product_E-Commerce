{{-- ============================================================ --}}
{{-- resources/views/admin/products/index.blade.php --}}
{{-- ============================================================ --}}
@extends('layouts.admin')
@section('title', 'Kelola Produk')

@section('content')
<div class="flex items-center justify-between mb-5">
    <p class="text-sm text-gray-500">Total: {{ $products->total() }} produk</p>
    <a href="{{ route('admin.products.create') }}"
       class="bg-indigo-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-indigo-700 flex items-center gap-1">
        <i class="ti ti-plus"></i> Tambah Produk
    </a>
</div>

<div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-xs text-gray-400 border-b border-gray-100">
            <tr>
                <th class="text-left px-4 py-3">Produk</th>
                <th class="text-left px-4 py-3">Kategori</th>
                <th class="text-left px-4 py-3">Harga</th>
                <th class="text-left px-4 py-3">Terjual</th>
                <th class="text-left px-4 py-3">Status</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($products as $product)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <p class="font-medium text-gray-700 line-clamp-1">{{ $product->name }}</p>
                        <p class="text-xs text-gray-400">{{ strtoupper($product->file_type) }} · {{ $product->file_size_label }}</p>
                    </td>
                    <td class="px-4 py-3 text-gray-500">{{ $product->category->name }}</td>
                    <td class="px-4 py-3 font-semibold text-indigo-600">{{ $product->formatted_price }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $product->total_sales }}x</td>
                    <td class="px-4 py-3">
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $product->is_active ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 flex items-center gap-2 justify-end">
                        <a href="{{ route('admin.products.edit', $product->id) }}"
                           class="text-indigo-500 hover:text-indigo-700 text-xs">
                            <i class="ti ti-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.products.destroy', $product->id) }}"
                              onsubmit="return confirm('Hapus produk ini?')">
                            @csrf @method('DELETE')
                            <button class="text-red-400 hover:text-red-600 text-xs">
                                <i class="ti ti-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="px-4 py-3 border-t border-gray-50">{{ $products->links() }}</div>
</div>
@endsection
