@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="mx-auto max-w-[1280px] px-6 py-10">
    <div class="mb-10">
        <h1 class="font-display text-[clamp(1.75rem,3vw,2rem)] font-bold tracking-[-0.03em]">Dashboard Produk</h1>
        <p class="mt-1 text-sm text-text-secondary">Selamat datang kembali, {{ Auth::user()->name }}</p>
    </div>

    @if ($lowStockProducts->isNotEmpty())
    <div class="mb-6 rounded-xl border border-warning/20 bg-warning/5 px-5 py-4">
        <div class="flex items-start gap-3">
            <div class="mt-0.5 shrink-0">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-warning"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            </div>
            <div class="flex-1">
                <p class="text-sm font-medium text-warning">Stok Menipis</p>
                <p class="mt-1 text-xs text-text-secondary">{{ $lowStockProducts->count() }} produk hampir habis. Segera lakukan restock.</p>
                <div class="mt-2 flex flex-wrap gap-2">
                    @foreach ($lowStockProducts as $p)
                        <span class="inline-flex items-center gap-1 rounded-full bg-warning/10 px-2.5 py-0.5 text-[11px] font-medium text-warning">
                            {{ $p->name }}
                            <span class="opacity-70">({{ $p->stock }})</span>
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="mb-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-[12px] border border-border bg-surface p-6 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-[0_8px_30px_rgba(0,0,0,0.08)]">
            <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-primary"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4zM3 6h18M16 10a4 4 0 0 1-8 0"/></svg>
            </div>
            <p class="text-xs font-medium uppercase tracking-wider text-text-secondary">Total Produk</p>
            <p class="mt-1 font-display text-3xl font-bold tracking-[-0.03em]">{{ $totalProduk }}</p>
            <p class="mt-0.5 text-xs text-text-secondary">Semua produk</p>
        </div>

        <div class="rounded-[12px] border border-border bg-surface p-6 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-[0_8px_30px_rgba(0,0,0,0.08)]">
            <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-lg bg-success/10">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-success"><path d="M12 20V10M18 20V4M6 20v-4"/></svg>
            </div>
            <p class="text-xs font-medium uppercase tracking-wider text-text-secondary">Total Terjual</p>
            <p class="mt-1 font-display text-3xl font-bold tracking-[-0.03em]">{{ number_format($totalTerjual) }}</p>
            <p class="mt-0.5 text-xs text-text-secondary">Semua waktu</p>
        </div>

        <div class="rounded-[12px] border border-border bg-surface p-6 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-[0_8px_30px_rgba(0,0,0,0.08)]">
            <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-lg bg-warning/10">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-warning"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
            </div>
            <p class="text-xs font-medium uppercase tracking-wider text-text-secondary">Sisa Produk</p>
            <p class="mt-1 font-display text-3xl font-bold tracking-[-0.03em]">{{ number_format($sisaProduk) }}</p>
            <p class="mt-0.5 text-xs text-text-secondary">Stok tersedia</p>
        </div>

        <div class="rounded-[12px] border border-border bg-surface p-6 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-[0_8px_30px_rgba(0,0,0,0.08)]">
            <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-lg bg-secondary/10">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-secondary"><path d="M4 7V4h16v3"/><path d="M9 20h6"/><path d="M12 4v16"/></svg>
            </div>
            <p class="text-xs font-medium uppercase tracking-wider text-text-secondary">Jumlah Jenis</p>
            <p class="mt-1 font-display text-3xl font-bold tracking-[-0.03em]">{{ $jumlahJenis }}</p>
            <p class="mt-0.5 text-xs text-text-secondary">Kategori produk</p>
        </div>
    </div>

    <div class="mb-10">
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <h2 class="font-display text-xl font-bold tracking-[-0.03em]">Daftar Produk</h2>
            <form method="GET" action="{{ route('admin.dashboard') }}" class="flex flex-wrap items-center gap-3">
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
                    <a href="{{ route('admin.dashboard') }}" class="rounded-xl border border-border px-4 py-2 text-sm font-medium text-text-secondary hover:bg-bg/50 transition-colors">Reset</a>
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
                        <td class="px-4 py-3 text-text-secondary">
                            <span class="inline-flex rounded-full bg-neutral/10 px-2.5 py-0.5 text-xs font-medium text-text-secondary">{{ $product->category->name }}</span>
                        </td>
                        <td class="px-4 py-3 text-right font-mono text-sm">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right">{{ $product->stock }}</td>
                        <td class="px-4 py-3 text-right">{{ $product->sold }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-sm text-text-secondary">Tidak ada produk yang ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
