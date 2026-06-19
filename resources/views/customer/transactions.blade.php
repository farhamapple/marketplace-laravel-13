@extends('layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')
<div class="mx-auto max-w-[1280px] px-6 py-10">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="font-display text-[clamp(1.75rem,3vw,2rem)] font-bold tracking-[-0.03em]">Riwayat Transaksi</h1>
            <p class="mt-1 text-sm text-text-secondary">Daftar pembelian yang sudah dibayar</p>
        </div>
        <a href="{{ route('customer.dashboard') }}" class="inline-flex items-center gap-1 text-sm text-text-secondary hover:text-text-primary transition-colors">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
            Kembali ke Marketplace
        </a>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-xl border border-success/20 bg-success/5 px-4 py-3 text-sm text-success">{{ session('success') }}</div>
    @endif

    <div class="overflow-hidden rounded-[12px] border border-border bg-surface">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-border bg-bg">
                    <th class="px-4 py-3 text-left font-medium text-text-secondary">Produk</th>
                    <th class="px-4 py-3 text-left font-medium text-text-secondary">Kategori</th>
                    <th class="px-4 py-3 text-center font-medium text-text-secondary">Tipe</th>
                    <th class="px-4 py-3 text-right font-medium text-text-secondary">Qty</th>
                    <th class="px-4 py-3 text-right font-medium text-text-secondary">Total</th>
                    <th class="px-4 py-3 text-left font-medium text-text-secondary">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transactions as $tx)
                <tr class="border-b border-border last:border-0 hover:bg-bg/50 transition-colors">
                    <td class="px-4 py-3 font-medium">{{ $tx->product->name }}</td>
                    <td class="px-4 py-3">
                        <span class="inline-flex rounded-full bg-neutral/10 px-2.5 py-0.5 text-xs font-medium text-text-secondary">{{ $tx->product->category->name }}</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-flex rounded-full bg-success/10 px-2.5 py-0.5 text-xs font-medium text-success">Pembelian</span>
                    </td>
                    <td class="px-4 py-3 text-right font-mono text-sm">{{ $tx->quantity }}</td>
                    <td class="px-4 py-3 text-right font-mono text-sm">Rp {{ number_format($tx->total, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-text-secondary text-xs">{{ $tx->created_at->format('d M Y H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-16 text-center text-sm text-text-secondary">
                        <div class="mb-3 flex justify-center">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="text-text-secondary/40"><path d="M12 20V10M18 20V4M6 20v-4"/></svg>
                        </div>
                        Belum ada transaksi.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($transactions->isNotEmpty())
    <div class="mt-6 text-right">
        <p class="text-sm text-text-secondary">
            Total transaksi: <span class="font-semibold text-text-primary">{{ $transactions->count() }}</span>
        </p>
    </div>
    @endif
</div>
@endsection
