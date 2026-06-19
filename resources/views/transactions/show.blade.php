@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="mx-auto max-w-[640px] px-6 py-10">
    <a href="{{ route('admin.transactions.index') }}" class="mb-6 inline-flex items-center gap-1 text-sm text-text-secondary hover:text-text-primary transition-colors">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
        Kembali
    </a>

    <h1 class="font-display text-[clamp(1.75rem,3vw,2rem)] font-bold tracking-[-0.03em]">Detail Transaksi</h1>

    <div class="mt-8 space-y-4">
        <div class="rounded-[12px] border border-border bg-surface p-6">
            <div class="mb-6 flex items-center justify-between">
                <span class="text-xs text-text-secondary">ID Transaksi</span>
                <span class="font-mono text-xs">#{{ $transaction->id }}</span>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-text-secondary">Produk</p>
                    <p class="mt-1 font-medium">{{ $transaction->product->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-text-secondary">Kategori</p>
                    <p class="mt-1 text-text-secondary">{{ $transaction->product->category->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-text-secondary">Customer</p>
                    <p class="mt-1">
                        @if ($transaction->user)
                            <span class="font-medium">{{ $transaction->user->name }}</span>
                            <span class="text-text-secondary text-xs">({{ $transaction->user->email }})</span>
                        @else
                            <span class="text-text-secondary">-</span>
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-text-secondary">Tipe</p>
                    <p class="mt-1">
                        @if ($transaction->type === 'sale')
                            <span class="inline-flex rounded-full bg-success/10 px-2.5 py-0.5 text-xs font-medium text-success">Penjualan</span>
                        @else
                            <span class="inline-flex rounded-full bg-warning/10 px-2.5 py-0.5 text-xs font-medium text-warning">Pembelian</span>
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-text-secondary">Jumlah</p>
                    <p class="mt-1 font-mono">{{ $transaction->quantity }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-text-secondary">Total</p>
                    <p class="mt-1 font-mono text-lg font-bold">Rp {{ number_format($transaction->total, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-text-secondary">Tanggal</p>
                    <p class="mt-1 text-text-secondary">{{ $transaction->created_at->format('d M Y H:i') }}</p>
                </div>
            </div>

            @if ($transaction->notes)
            <div class="mt-6 border-t border-border pt-4">
                <p class="text-xs font-medium uppercase tracking-wider text-text-secondary">Catatan</p>
                <p class="mt-1 text-sm text-text-secondary">{{ $transaction->notes }}</p>
            </div>
            @endif
        </div>

        <div class="flex items-center gap-3">
            <form method="POST" action="{{ route('admin.transactions.destroy', $transaction) }}" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="rounded-xl border border-red-200 px-6 py-2.5 text-sm font-medium text-red-500 hover:bg-red-50 transition-colors cursor-pointer">Hapus Transaksi</button>
            </form>
            <a href="{{ route('admin.transactions.index') }}" class="rounded-xl border border-border px-6 py-2.5 text-sm font-medium text-text-secondary hover:bg-bg transition-colors">Kembali</a>
        </div>
    </div>
</div>
@endsection
