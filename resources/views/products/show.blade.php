@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="mx-auto max-w-[960px] px-6 py-10">
    <a href="{{ url()->previous() }}" class="mb-6 inline-flex items-center gap-1 text-sm text-text-secondary hover:text-text-primary transition-colors">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
        Kembali
    </a>

    <div class="grid gap-8 md:grid-cols-2">
        <div class="flex h-64 items-center justify-center rounded-[12px] border border-border bg-gradient-to-br from-primary/5 to-secondary/5 overflow-hidden md:h-80">
            @if ($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
            @else
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" class="text-primary/30"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4zM3 6h18M16 10a4 4 0 0 1-8 0"/></svg>
            @endif
        </div>

        <div>
            <div class="mb-2">
                <span class="inline-flex rounded-full bg-neutral/10 px-2.5 py-0.5 text-xs font-medium text-text-secondary">{{ $product->category->name }}</span>
            </div>
            <h1 class="font-display text-[clamp(1.75rem,3vw,2.5rem)] font-bold tracking-[-0.03em]">{{ $product->name }}</h1>
            <p class="mt-4 font-mono text-3xl font-bold text-primary">Rp {{ number_format($product->price, 0, ',', '.') }}</p>

            <div class="mt-6 flex items-center gap-6 text-sm text-text-secondary">
                <span class="flex items-center gap-1.5">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20V10M18 20V4M6 20v-4"/></svg>
                    Stok: <strong class="text-text-primary">{{ $product->stock }}</strong>
                </span>
                <span class="flex items-center gap-1.5">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20V10M18 20V4M6 20v-4"/></svg>
                    Terjual: <strong class="text-text-primary">{{ $product->sold }}</strong>
                </span>
            </div>

            @auth
                @if (!Auth::user()->isAdmin())
                    <form method="POST" action="{{ route('customer.cart.store') }}" class="mt-8 flex items-center gap-3">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="flex items-center rounded-xl border border-border overflow-hidden">
                            <button type="button" class="qty-detail-minus px-3 py-2.5 text-text-secondary hover:bg-bg transition-colors cursor-pointer" data-target="detail-qty">−</button>
                            <input type="number" name="quantity" id="detail-qty" value="1" min="1" max="{{ $product->stock }}" class="w-14 border-x border-border bg-bg px-2 py-2.5 text-center text-sm outline-none [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none" {{ $product->stock < 1 ? 'disabled' : '' }}>
                            <button type="button" class="qty-detail-plus px-3 py-2.5 text-text-secondary hover:bg-bg transition-colors cursor-pointer" data-target="detail-qty">+</button>
                        </div>
                        <button type="submit" class="flex-1 rounded-xl bg-primary px-6 py-2.5 text-sm font-medium text-white hover:bg-primary-hover transition-colors cursor-pointer" {{ $product->stock < 1 ? 'disabled' : '' }}>
                            {{ $product->stock < 1 ? 'Stok Habis' : '+ Tambah ke Keranjang' }}
                        </button>
                    </form>
                @endif
            @else
                <div class="mt-8">
                    <a href="{{ route('login') }}" class="inline-flex rounded-xl bg-primary px-6 py-2.5 text-sm font-medium text-white hover:bg-primary-hover transition-colors">Masuk untuk Membeli</a>
                </div>
            @endauth
        </div>
    </div>
</div>

@push('scripts')
<script>
document.querySelectorAll('.qty-detail-minus, .qty-detail-plus').forEach(function (btn) {
    btn.addEventListener('click', function () {
        const targetId = this.dataset.target;
        const input = document.getElementById(targetId);
        const min = parseInt(input.min) || 1;
        const max = parseInt(input.max) || 9999;
        let val = parseInt(input.value) || min;

        if (this.classList.contains('qty-detail-minus')) {
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