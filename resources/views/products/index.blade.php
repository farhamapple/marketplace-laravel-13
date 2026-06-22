@extends('layouts.app')

@section('title', 'Produk')

@section('content')
<div class="mx-auto max-w-[1280px] px-6 py-10">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="font-display text-[clamp(1.75rem,3vw,2rem)] font-bold tracking-[-0.03em]">Produk</h1>
            <p class="mt-1 text-sm text-text-secondary">Kelola produk</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-primary px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-primary/25 hover:bg-primary-hover hover:shadow-xl hover:shadow-primary/30 transition-all duration-200">+ Tambah Produk</a>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-xl border border-success/20 bg-success/5 px-4 py-3 text-sm text-success">{{ session('success') }}</div>
    @endif

    <div class="mb-4 flex flex-wrap items-center gap-3">
        <form method="GET" action="{{ route('admin.products.index') }}" class="flex flex-wrap items-center gap-3">
            <input type="text" name="search" placeholder="Cari nama produk..." value="{{ request('search') }}" class="rounded-xl border border-border bg-bg px-3 py-2 text-sm placeholder:text-text-secondary/50 focus:outline-none focus:ring-2 focus:ring-primary/20 w-48">

            <select name="category_id" class="rounded-xl border border-border bg-bg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>

            <select name="stock" class="rounded-xl border border-border bg-bg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20">
                <option value="">Semua Stok</option>
                <option value="in" {{ request('stock') == 'in' ? 'selected' : '' }}>Tersedia</option>
                <option value="low" {{ request('stock') == 'low' ? 'selected' : '' }}>Hampir Habis</option>
                <option value="out" {{ request('stock') == 'out' ? 'selected' : '' }}>Habis</option>
            </select>

            <button type="submit" class="rounded-xl bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-primary/90 transition-colors">Filter</button>

            @if (request()->anyFilled(['search', 'category_id', 'stock']))
                <a href="{{ route('admin.products.index') }}" class="rounded-xl border border-border px-4 py-2 text-sm font-medium text-text-secondary hover:bg-bg/50 transition-colors">Reset</a>
            @endif
        </form>
    </div>

    <div class="overflow-hidden rounded-[12px] border border-border bg-surface">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-border bg-bg">
                    <th class="px-4 py-3 text-left font-medium text-text-secondary w-12">Gambar</th>
                    <th class="px-4 py-3 text-left font-medium text-text-secondary">Nama</th>
                    <th class="px-4 py-3 text-left font-medium text-text-secondary">Kategori</th>
                    <th class="px-4 py-3 text-right font-medium text-text-secondary">Harga</th>
                    <th class="px-4 py-3 text-right font-medium text-text-secondary">Stok</th>
                    <th class="px-4 py-3 text-right font-medium text-text-secondary">Terjual</th>
                    <th class="px-4 py-3 text-right font-medium text-text-secondary">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                <tr class="border-b border-border last:border-0 hover:bg-bg/50 transition-colors">
                    <td class="px-4 py-3">
                        @if ($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="h-10 w-10 rounded-lg object-cover border border-border">
                        @else
                            <div class="h-10 w-10 rounded-lg bg-neutral/10 flex items-center justify-center">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-text-secondary/40"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4zM3 6h18M16 10a4 4 0 0 1-8 0"/></svg>
                            </div>
                        @endif
                    </td>
                    <td class="px-4 py-3 font-medium">{{ $product->name }}</td>
                    <td class="px-4 py-3">
                        <span class="inline-flex rounded-full bg-neutral/10 px-2.5 py-0.5 text-xs font-medium text-text-secondary">{{ $product->category->name }}</span>
                    </td>
                    <td class="px-4 py-3 text-right font-mono text-sm">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-right">{{ $product->stock }}</td>
                    <td class="px-4 py-3 text-right">{{ $product->sold }}</td>
                    <td class="px-4 py-3 text-right">
                        <div class="inline-flex items-center gap-1">
                            <a href="{{ route('admin.products.edit', $product) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-text-secondary hover:bg-primary/5 hover:text-primary transition-colors" title="Edit">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.85 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                            </a>
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-text-secondary hover:bg-red-500/5 hover:text-red-500 transition-colors cursor-pointer" title="Hapus">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-10 text-center text-sm text-text-secondary">Tidak ada produk yang ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
