{{-- ============================================================ --}}
{{-- resources/views/admin/products/_form.blade.php --}}
{{-- ============================================================ --}}
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
    <select name="category_id" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300" required>
        <option value="">Pilih kategori</option>
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                {{ $cat->name }}
            </option>
        @endforeach
    </select>
    @error('category_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
    <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}"
           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300" required>
    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
    <textarea name="description" rows="4"
              class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 resize-none">{{ old('description', $product->description ?? '') }}</textarea>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp)</label>
    <input type="number" name="price" value="{{ old('price', $product->price ?? '') }}" min="0"
           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300" required>
    @error('price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Thumbnail (opsional)</label>
    <input type="file" name="thumbnail" accept="image/*"
           class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100">
    @if(isset($product) && $product->thumbnail)
        <img src="{{ asset('storage/' . $product->thumbnail) }}" class="mt-2 h-20 rounded-lg object-cover">
    @endif
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">
        File Produk {{ isset($product) ? '(kosongkan jika tidak ingin mengganti)' : '' }}
    </label>
    <input type="file" name="file" accept=".pdf,.zip,.mp4,.epub,.docx,.pptx"
           class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-gray-50 file:text-gray-600 hover:file:bg-gray-100"
           {{ !isset($product) ? 'required' : '' }}>
    @if(isset($product))
        <p class="text-xs text-gray-400 mt-1">File saat ini: {{ $product->file_path }} ({{ $product->file_size_label }})</p>
    @endif
    @error('file')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
</div>

<div class="flex items-center gap-2">
    <input type="checkbox" name="is_active" id="is_active" value="1"
           {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}
           class="rounded border-gray-300 text-indigo-600">
    <label for="is_active" class="text-sm text-gray-700">Produk aktif (tampil di katalog)</label>
</div>
