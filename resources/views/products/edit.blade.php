@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<div class="mx-auto max-w-[640px] px-6 py-10">
    <a href="{{ route('admin.products.index') }}" class="mb-6 inline-flex items-center gap-1 text-sm text-text-secondary hover:text-text-primary transition-colors">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
        Kembali
    </a>

    <h1 class="font-display text-[clamp(1.75rem,3vw,2rem)] font-bold tracking-[-0.03em]">Edit Produk</h1>
    <p class="mt-1 text-sm text-text-secondary">Perbarui informasi produk</p>

    <form method="POST" action="{{ route('admin.products.update', $product) }}" class="mt-8 space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="mb-1.5 block text-sm font-medium">Nama Produk</label>
            <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" class="w-full rounded-xl border border-border bg-surface px-4 py-2.5 text-sm outline-none focus:border-primary/50 focus:ring-2 focus:ring-primary/10 transition-all" required>
            @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="category_id" class="mb-1.5 block text-sm font-medium">Kategori</label>
            <select name="category_id" id="category_id" class="w-full rounded-xl border border-border bg-surface px-4 py-2.5 text-sm outline-none focus:border-primary/50 focus:ring-2 focus:ring-primary/10 transition-all" required>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="price" class="mb-1.5 block text-sm font-medium">Harga</label>
                <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}" step="0.01" min="0" class="w-full rounded-xl border border-border bg-surface px-4 py-2.5 text-sm outline-none focus:border-primary/50 focus:ring-2 focus:ring-primary/10 transition-all" required>
                @error('price') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="stock" class="mb-1.5 block text-sm font-medium">Stok</label>
                <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" min="0" class="w-full rounded-xl border border-border bg-surface px-4 py-2.5 text-sm outline-none focus:border-primary/50 focus:ring-2 focus:ring-primary/10 transition-all" required>
                @error('stock') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="rounded-xl bg-primary px-6 py-2.5 text-sm font-medium text-white hover:bg-primary-hover transition-colors cursor-pointer">Perbarui</button>
            <a href="{{ route('admin.products.index') }}" class="rounded-xl border border-border px-6 py-2.5 text-sm font-medium text-text-secondary hover:bg-bg transition-colors">Batal</a>
        </div>
    </form>
</div>
@endsection
