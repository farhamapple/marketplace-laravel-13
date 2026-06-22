@extends('layouts.app')

@section('title', 'Marketplace With Laravel 13')

@section('content')
<div class="mx-auto max-w-[1280px] px-6">
    {{-- Hero --}}
    <section class="py-16 md:py-20 lg:py-24">
        <div class="mx-auto max-w-3xl text-center">
            <h1 class="font-display text-[clamp(2.5rem,5vw,4.5rem)] font-bold leading-[1.05] tracking-[-0.04em]">
                Marketplace With<br>
                <span class="text-primary">Laravel 13</span>
            </h1>
            <p class="mt-6 text-lg text-text-secondary leading-relaxed max-w-2xl mx-auto">
                Platform jual-beli produk berbasis Laravel 13. Temukan berbagai produk menarik dengan harga terbaik.
            </p>
            <div class="mt-10 flex items-center justify-center gap-4">
                <a href="{{ url('/register') }}" class="rounded-xl bg-primary px-6 py-3 text-sm font-medium text-white hover:bg-primary-hover transition-colors">Mulai Belanja</a>
                <a href="{{ url('/login') }}" class="rounded-xl border border-border px-6 py-3 text-sm font-medium text-text-secondary hover:bg-bg transition-colors">Masuk</a>
            </div>
        </div>
    </section>

    {{-- Stats --}}
    <section class="mb-12">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-[12px] border border-border bg-surface p-5">
                <p class="text-xs font-medium uppercase tracking-wider text-text-secondary">Total Produk</p>
                <p class="mt-1 font-display text-2xl font-bold tracking-[-0.03em]">{{ number_format($totalProduk) }}</p>
                <p class="mt-0.5 text-xs text-text-secondary">Produk tersedia</p>
            </div>
            <div class="rounded-[12px] border border-border bg-surface p-5">
                <p class="text-xs font-medium uppercase tracking-wider text-text-secondary">Total Stok</p>
                <p class="mt-1 font-display text-2xl font-bold tracking-[-0.03em]">{{ number_format($totalStok) }}</p>
                <p class="mt-0.5 text-xs text-text-secondary">Barang di gudang</p>
            </div>
            <div class="rounded-[12px] border border-border bg-surface p-5">
                <p class="text-xs font-medium uppercase tracking-wider text-text-secondary">Total Terjual</p>
                <p class="mt-1 font-display text-2xl font-bold tracking-[-0.03em]">{{ number_format($totalTerjual) }}</p>
                <p class="mt-0.5 text-xs text-text-secondary">Dari transaksi real</p>
            </div>
            <div class="rounded-[12px] border border-border bg-surface p-5">
                <p class="text-xs font-medium uppercase tracking-wider text-text-secondary">Kategori</p>
                <p class="mt-1 font-display text-2xl font-bold tracking-[-0.03em]">{{ $totalKategori }}</p>
                <p class="mt-0.5 text-xs text-text-secondary">Jenis produk</p>
            </div>
        </div>
    </section>

    {{-- Product Grid --}}
    <section class="pb-20">
        <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
            <h2 class="font-display text-[clamp(1.5rem,3vw,2rem)] font-bold tracking-[-0.03em]">Semua Produk</h2>
            <form method="GET" action="{{ url('/') }}" class="flex items-center gap-2">
                <input type="text" name="search" placeholder="Cari produk..." value="{{ request('search') }}" class="rounded-xl border border-border bg-surface px-4 py-2.5 text-sm w-56 placeholder:text-text-secondary/50 outline-none focus:border-primary/50 focus:ring-2 focus:ring-primary/10 transition-all">
                <button type="submit" class="rounded-xl bg-primary px-4 py-2.5 text-sm font-medium text-white hover:bg-primary-hover transition-colors cursor-pointer">Cari</button>
                @if (request('search'))
                    <a href="{{ url('/') }}" class="rounded-xl border border-border px-4 py-2.5 text-sm font-medium text-text-secondary hover:bg-bg transition-colors">Reset</a>
                @endif
            </form>
        </div>
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse ($products as $product)
            <div class="rounded-[12px] border border-border bg-surface p-5 transition-all duration-200 hover:-translate-y-1 hover:shadow-[0_8px_30px_rgba(0,0,0,0.08)]">
                <div class="mb-4 flex h-32 items-center justify-center rounded-lg bg-gradient-to-br from-primary/5 to-secondary/5 overflow-hidden">
                    @if ($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                    @else
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" class="text-primary/30"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4zM3 6h18M16 10a4 4 0 0 1-8 0"/></svg>
                    @endif
                </div>
                <div class="mb-1 flex items-start justify-between gap-2">
                    <a href="{{ route('product.show', $product) }}" class="font-display font-semibold tracking-tight hover:text-primary transition-colors">{{ $product->name }}</a>
                    <span class="shrink-0 rounded-full bg-neutral/10 px-2 py-0.5 text-[10px] font-medium text-text-secondary">{{ $product->category->name }}</span>
                </div>
                <p class="mb-3 font-mono text-lg font-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                <div class="flex items-center gap-3 text-xs text-text-secondary">
                    <span class="flex items-center gap-1">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20V10M18 20V4M6 20v-4"/></svg>
                        Stok: {{ $product->stock }}
                    </span>
                    <span class="flex items-center gap-1">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20V10M18 20V4M6 20v-4"/></svg>
                        Terjual: {{ $product->sold }}
                    </span>
                </div>
            </div>
            @empty
            <div class="col-span-full py-20 text-center text-sm text-text-secondary">
                Belum ada produk tersedia.
            </div>
            @endforelse
        </div>

        <div class="mt-10 flex justify-center">
            {{ $products->links() }}
        </div>
    </section>

    {{-- Footer --}}
    <footer class="border-t border-border py-8 text-sm text-text-secondary">
        <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
            <p>&copy; {{ date('Y') }} Marketplace With Laravel 13.</p>
            <div class="flex items-center gap-6">
                <a href="{{ url('/login') }}" class="hover:text-text-primary transition-colors">Masuk</a>
                <a href="{{ url('/register') }}" class="hover:text-text-primary transition-colors">Daftar</a>
            </div>
        </div>
    </footer>
</div>
@endsection
