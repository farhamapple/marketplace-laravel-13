@extends('layouts.app')

@section('title', 'Transaksi Baru')

@section('content')
<div class="mx-auto max-w-[640px] px-6 py-10">
    <a href="{{ route('admin.transactions.index') }}" class="mb-6 inline-flex items-center gap-1 text-sm text-text-secondary hover:text-text-primary transition-colors">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
        Kembali
    </a>

    <h1 class="font-display text-[clamp(1.75rem,3vw,2rem)] font-bold tracking-[-0.03em]">Transaksi Baru</h1>
    <p class="mt-1 text-sm text-text-secondary">Catat penjualan atau pembelian produk</p>

    <form method="POST" action="{{ route('admin.transactions.store') }}" class="mt-8 space-y-5">
        @csrf

        <div>
            <label for="product_id" class="mb-1.5 block text-sm font-medium">Produk</label>
            <select name="product_id" id="product_id" class="w-full rounded-xl border border-border bg-surface px-4 py-2.5 text-sm outline-none focus:border-primary/50 focus:ring-2 focus:ring-primary/10 transition-all" required>
                <option value="">Pilih produk</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                        {{ $product->name }} — {{ $product->category->name }} (Stok: {{ $product->stock }})
                    </option>
                @endforeach
            </select>
            @error('product_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="type" class="mb-1.5 block text-sm font-medium">Tipe Transaksi</label>
            <div class="grid grid-cols-2 gap-3">
                <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-border bg-surface px-4 py-3 text-sm has-[:checked]:border-primary has-[:checked]:bg-primary/5 transition-all">
                    <input type="radio" name="type" value="sale" {{ old('type') === 'sale' ? 'checked' : '' }} class="text-primary" required>
                    <div>
                        <p class="font-medium text-text-primary">Penjualan</p>
                        <p class="text-xs text-text-secondary">Produk terjual</p>
                    </div>
                </label>
                <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-border bg-surface px-4 py-3 text-sm has-[:checked]:border-primary has-[:checked]:bg-primary/5 transition-all">
                    <input type="radio" name="type" value="purchase" {{ old('type') === 'purchase' ? 'checked' : '' }} class="text-primary">
                    <div>
                        <p class="font-medium text-text-primary">Pembelian</p>
                        <p class="text-xs text-text-secondary">Stok masuk</p>
                    </div>
                </label>
            </div>
            @error('type') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="quantity" class="mb-1.5 block text-sm font-medium">Jumlah</label>
            <input type="number" name="quantity" id="quantity" value="{{ old('quantity') }}" min="1" class="w-full rounded-xl border border-border bg-surface px-4 py-2.5 text-sm outline-none focus:border-primary/50 focus:ring-2 focus:ring-primary/10 transition-all" placeholder="1" required>
            @error('quantity') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="notes" class="mb-1.5 block text-sm font-medium">Catatan</label>
            <textarea name="notes" id="notes" rows="2" class="w-full rounded-xl border border-border bg-surface px-4 py-2.5 text-sm outline-none focus:border-primary/50 focus:ring-2 focus:ring-primary/10 transition-all" placeholder="Catatan (opsional)">{{ old('notes') }}</textarea>
            @error('notes') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="rounded-xl bg-primary px-6 py-2.5 text-sm font-medium text-white hover:bg-primary-hover transition-colors cursor-pointer">Simpan Transaksi</button>
            <a href="{{ route('admin.transactions.index') }}" class="rounded-xl border border-border px-6 py-2.5 text-sm font-medium text-text-secondary hover:bg-bg transition-colors">Batal</a>
        </div>
    </form>
</div>
@endsection
