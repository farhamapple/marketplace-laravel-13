@extends('layouts.app')

@section('title', 'Transaksi')

@section('content')
<div class="mx-auto max-w-[1280px] px-6 py-10">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="font-display text-[clamp(1.75rem,3vw,2rem)] font-bold tracking-[-0.03em]">Transaksi</h1>
            <p class="mt-1 text-sm text-text-secondary">Catat transaksi penjualan & pembelian</p>
        </div>
        <a href="{{ route('admin.transactions.create') }}" class="btn btn-primary inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-medium">+ Transaksi Baru</a>
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
                    <th class="px-4 py-3 text-left font-medium text-text-secondary">Customer</th>
                    <th class="px-4 py-3 text-center font-medium text-text-secondary">Tipe</th>
                    <th class="px-4 py-3 text-right font-medium text-text-secondary">Qty</th>
                    <th class="px-4 py-3 text-right font-medium text-text-secondary">Total</th>
                    <th class="px-4 py-3 text-left font-medium text-text-secondary">Tanggal</th>
                    <th class="px-4 py-3 text-right font-medium text-text-secondary">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transactions as $tx)
                <tr class="border-b border-border last:border-0 hover:bg-bg/50 transition-colors">
                    <td class="px-4 py-3 font-medium">
                        <a href="{{ route('admin.transactions.show', $tx) }}" class="hover:text-primary transition-colors">{{ $tx->product->name }}</a>
                    </td>
                    <td class="px-4 py-3">
                        <span class="inline-flex rounded-full bg-neutral/10 px-2.5 py-0.5 text-xs font-medium text-text-secondary">{{ $tx->product->category->name }}</span>
                    </td>
                    <td class="px-4 py-3 text-text-secondary text-xs">
                        @if ($tx->user)
                            <div class="flex items-center gap-2">
                                <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-primary/10 text-[10px] font-semibold text-primary">{{ substr($tx->user->name, 0, 1) }}</span>
                                <span>{{ $tx->user->name }}<br><span class="text-text-secondary/60">{{ $tx->user->email }}</span></span>
                            </div>
                        @else
                            <span class="text-text-secondary/50">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if ($tx->type === 'sale')
                            <span class="inline-flex rounded-full bg-success/10 px-2.5 py-0.5 text-xs font-medium text-success">Penjualan</span>
                        @else
                            <span class="inline-flex rounded-full bg-warning/10 px-2.5 py-0.5 text-xs font-medium text-warning">Pembelian</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right font-mono text-sm">{{ $tx->quantity }}</td>
                    <td class="px-4 py-3 text-right font-mono text-sm">Rp {{ number_format($tx->total, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-text-secondary text-xs">{{ $tx->created_at->format('d M Y H:i') }}</td>
                    <td class="px-4 py-3 text-right">
                        <div class="inline-flex items-center gap-2">
                            <a href="{{ route('admin.transactions.show', $tx) }}" class="rounded-lg px-3 py-1.5 text-xs font-medium text-text-secondary hover:bg-bg transition-colors">Detail</a>
                            <form method="POST" action="{{ route('admin.transactions.destroy', $tx) }}" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-lg px-3 py-1.5 text-xs font-medium text-red-500 hover:bg-red-500/5 transition-colors cursor-pointer">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-10 text-center text-sm text-text-secondary">Belum ada transaksi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
