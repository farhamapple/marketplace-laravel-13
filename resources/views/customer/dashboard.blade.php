@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mx-auto max-w-[1280px] px-6 py-10">
    <div class="mb-10">
        <h1 class="font-display text-[clamp(1.75rem,3vw,2rem)] font-bold tracking-[-0.03em]">Marketplace</h1>
        <p class="mt-1 text-sm text-text-secondary">Cari dan pilih produk untuk dibeli, {{ Auth::user()->name }}</p>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-xl border border-success/20 bg-success/5 px-4 py-3 text-sm text-success">{{ session('success') }}</div>
    @endif

    <div class="mb-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-[12px] border border-border bg-surface p-5">
            <p class="text-xs font-medium uppercase tracking-wider text-text-secondary">Total Pembelian</p>
            <p class="mt-1 font-display text-2xl font-bold tracking-[-0.03em]">{{ number_format($totalPembelian) }}</p>
            <p class="mt-0.5 text-xs text-text-secondary">Barang yang sudah dibeli</p>
        </div>
        <div class="rounded-[12px] border border-border bg-surface p-5">
            <p class="text-xs font-medium uppercase tracking-wider text-text-secondary">Total Pengeluaran</p>
            <p class="mt-1 font-display text-2xl font-bold tracking-[-0.03em]">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
            <p class="mt-0.5 text-xs text-text-secondary">Dari {{ $totalTransaksi }} transaksi</p>
        </div>
        <div class="rounded-[12px] border border-border bg-surface p-5">
            <p class="text-xs font-medium uppercase tracking-wider text-text-secondary">Total Transaksi</p>
            <p class="mt-1 font-display text-2xl font-bold tracking-[-0.03em]">{{ $totalTransaksi }}</p>
            <p class="mt-0.5 text-xs text-text-secondary">Kali pembelian</p>
        </div>
        <div class="rounded-[12px] border border-border bg-surface p-5">
            <p class="text-xs font-medium uppercase tracking-wider text-text-secondary">Keranjang</p>
            <p class="mt-1 font-display text-2xl font-bold tracking-[-0.03em]">{{ $cartCount }}</p>
            <p class="mt-0.5 text-xs text-text-secondary">Item menunggu pembayaran</p>
        </div>
    </div>

    <div class="mb-8 flex flex-wrap gap-2">
        <button class="rounded-full border border-border bg-surface px-4 py-1.5 text-xs font-medium text-text-secondary hover:bg-bg transition-colors category-filter active" data-category="all">Semua</button>
        @foreach ($categories as $category)
            <button class="rounded-full border border-border bg-surface px-4 py-1.5 text-xs font-medium text-text-secondary hover:bg-bg transition-colors category-filter" data-category="{{ $category->slug }}">{{ $category->name }}</button>
        @endforeach
    </div>

    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4" id="product-grid">
        @forelse ($products as $product)
        <div class="product-card rounded-[12px] border border-border bg-surface p-5 transition-all duration-200 hover:-translate-y-1 hover:shadow-[0_8px_30px_rgba(0,0,0,0.08)]" data-category="{{ $product->category->slug }}">
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
            <div class="mb-4 flex items-center gap-3 text-xs text-text-secondary">
                <span class="flex items-center gap-1">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20V10M18 20V4M6 20v-4"/></svg>
                    Stok: {{ $product->stock }}
                </span>
                <span class="flex items-center gap-1">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20V10M18 20V4M6 20v-4"/></svg>
                    Terjual: {{ $product->sold }}
                </span>
            </div>
            <form method="POST" action="{{ route('customer.cart.store') }}" class="flex items-center gap-2">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="number" name="quantity" min="1" max="{{ $product->stock }}" value="1" class="w-16 rounded-lg border border-border bg-bg px-2 py-1.5 text-center text-sm outline-none focus:border-primary/50 transition-colors" {{ $product->stock < 1 ? 'disabled' : '' }}>
                <button type="submit" class="flex-1 rounded-lg bg-primary px-3 py-2 text-xs font-medium text-white hover:bg-primary-hover transition-colors cursor-pointer" {{ $product->stock < 1 ? 'disabled' : '' }}>
                    {{ $product->stock < 1 ? 'Habis' : '+ Keranjang' }}
                </button>
            </form>
        </div>
        @empty
        <div class="col-span-full py-20 text-center text-sm text-text-secondary">
            Belum ada produk tersedia.
        </div>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.category-filter').forEach(btn => {
    btn.addEventListener('click', function () {
        document.querySelectorAll('.category-filter').forEach(b => b.classList.remove('active', 'text-primary', 'border-primary'));
        this.classList.add('active', 'text-primary', 'border-primary');
        const cat = this.dataset.category;
        document.querySelectorAll('.product-card').forEach(card => {
            card.style.display = (cat === 'all' || card.dataset.category === cat) ? '' : 'none';
        });
    });
});
</script>
@endpush
