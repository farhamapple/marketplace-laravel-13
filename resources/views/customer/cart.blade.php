@extends('layouts.app')

@section('title', 'Keranjang')

@section('content')
<div class="mx-auto max-w-[960px] px-6 py-10">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="font-display text-[clamp(1.75rem,3vw,2rem)] font-bold tracking-[-0.03em]">Keranjang</h1>
            <p class="mt-1 text-sm text-text-secondary">{{ $items->count() }} item dalam keranjang</p>
        </div>
        <a href="{{ route('customer.dashboard') }}" class="inline-flex items-center gap-1 text-sm text-text-secondary hover:text-text-primary transition-colors">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
            Lanjut Belanja
        </a>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-xl border border-success/20 bg-success/5 px-4 py-3 text-sm text-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    @forelse ($items as $item)
    <div class="mb-4 rounded-[12px] border border-border bg-surface p-5 transition-all hover:shadow-[0_4px_16px_rgba(0,0,0,0.06)]">
        <div class="flex items-center gap-5">
            <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-primary/5 to-secondary/5">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" class="text-primary/30"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4zM3 6h18M16 10a4 4 0 0 1-8 0"/></svg>
            </div>
            <div class="min-w-0 flex-1">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h3 class="font-display font-semibold tracking-tight">{{ $item->product->name }}</h3>
                        <p class="text-xs text-text-secondary">{{ $item->product->category->name }}</p>
                    </div>
                    <p class="shrink-0 font-mono text-sm font-bold">Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</p>
                </div>
                <div class="mt-2 flex items-center gap-4 text-xs text-text-secondary">
                    <span>@ {{ number_format($item->product->price, 0, ',', '.') }} / item</span>
                    <span>Stok: {{ $item->product->stock }}</span>
                </div>
                <form method="POST" action="{{ route('customer.cart.update', $item) }}" class="mt-2 flex items-center gap-2">
                    @csrf
                    @method('PATCH')
                    <div class="flex items-center rounded-lg border border-border overflow-hidden">
                        <button type="button" class="qty-minus px-2.5 py-1.5 text-text-secondary hover:bg-bg transition-colors cursor-pointer text-sm leading-none" data-target="qty-{{ $item->id }}">−</button>
                        <input type="number" name="quantity" id="qty-{{ $item->id }}" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}" class="w-12 border-x border-border bg-bg px-2 py-1.5 text-center text-sm outline-none [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none">
                        <button type="button" class="qty-plus px-2.5 py-1.5 text-text-secondary hover:bg-bg transition-colors cursor-pointer text-sm leading-none" data-target="qty-{{ $item->id }}">+</button>
                    </div>
                    <button type="submit" class="rounded-lg px-3 py-1.5 text-xs font-medium text-primary hover:bg-primary/5 transition-colors cursor-pointer">Update</button>
                </form>
            </div>
            <form method="POST" action="{{ route('customer.cart.destroy', $item) }}" onsubmit="return confirm('Hapus item ini dari keranjang?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="rounded-lg px-3 py-1.5 text-xs font-medium text-red-500 hover:bg-red-50 transition-colors cursor-pointer">Hapus</button>
            </form>
        </div>
    </div>
    @empty
    <div class="rounded-[12px] border border-border bg-surface py-16 text-center">
        <div class="mb-4 flex justify-center">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="text-text-secondary/40"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4zM3 6h18M16 10a4 4 0 0 1-8 0"/></svg>
        </div>
        <p class="text-sm text-text-secondary">Keranjang masih kosong.</p>
        <a href="{{ route('customer.dashboard') }}" class="mt-4 inline-flex rounded-xl bg-primary px-5 py-2 text-sm font-medium text-white hover:bg-primary-hover transition-colors">Mulai Belanja</a>
    </div>
    @endforelse

    @if ($items->isNotEmpty())
    <div class="mt-8 rounded-[12px] border border-border bg-surface p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-text-secondary">Total Pembayaran</p>
                <p class="font-display text-2xl font-bold tracking-[-0.03em]">Rp {{ number_format($total, 0, ',', '.') }}</p>
                <p class="text-xs text-text-secondary">{{ $items->count() }} item</p>
            </div>
            <form method="POST" action="{{ route('customer.cart.checkout') }}" onsubmit="return confirm('Bayar semua item di keranjang?')">
                @csrf
                <button type="submit" class="rounded-xl bg-success px-8 py-3 text-sm font-medium text-white hover:bg-success/90 transition-colors cursor-pointer">
                    Bayar Sekarang
                </button>
            </form>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
document.querySelectorAll('.qty-minus, .qty-plus').forEach(function (btn) {
    btn.addEventListener('click', function () {
        const targetId = this.dataset.target;
        const input = document.getElementById(targetId);
        const min = parseInt(input.min) || 1;
        const max = parseInt(input.max) || 9999;
        let val = parseInt(input.value) || min;

        if (this.classList.contains('qty-minus')) {
            val = Math.max(min, val - 1);
        } else {
            val = Math.min(max, val + 1);
        }
        input.value = val;
    });
});
</script>
@endpush
@endsection
