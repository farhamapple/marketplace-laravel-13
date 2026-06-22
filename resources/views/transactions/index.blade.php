@extends('layouts.app')

@section('title', 'Transaksi')

@section('content')
<div class="mx-auto max-w-[1280px] px-6 py-10">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="font-display text-[clamp(1.75rem,3vw,2rem)] font-bold tracking-[-0.03em]">Transaksi</h1>
            <p class="mt-1 text-sm text-text-secondary">Catat transaksi penjualan & pembelian</p>
        </div>
        <a href="{{ route('admin.transactions.create') }}" class="btn btn-primary inline-flex items-center gap-2 rounded-xl bg-primary px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-primary/25 hover:bg-primary-hover hover:shadow-xl hover:shadow-primary/30 transition-all duration-200">+ Transaksi Baru</a>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-xl border border-success/20 bg-success/5 px-4 py-3 text-sm text-success">{{ session('success') }}</div>
    @endif

    <div class="mb-4">
        <form method="GET" action="{{ route('admin.transactions.index') }}" class="flex flex-wrap items-center gap-3">
            <div>
                <label class="mb-1 block text-xs font-medium text-text-secondary">Dari Tanggal</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="rounded-xl border border-border bg-bg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20">
            </div>
            <div>
                <label class="mb-1 block text-xs font-medium text-text-secondary">Sampai Tanggal</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="rounded-xl border border-border bg-bg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="rounded-xl bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-primary/90 transition-colors">Filter</button>
                @if (request()->anyFilled(['date_from', 'date_to']))
                    <a href="{{ route('admin.transactions.index') }}" class="rounded-xl border border-border px-4 py-2 text-sm font-medium text-text-secondary hover:bg-bg/50 transition-colors">Reset</a>
                @endif
            </div>
        </form>
    </div>

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
                        <button type="button" data-id="{{ $tx->id }}" class="btn-detail hover:text-primary transition-colors bg-transparent border-none cursor-pointer font-medium text-start p-0">{{ $tx->product->name }}</button>
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
                        <div class="inline-flex items-center gap-1">
                            <button type="button" data-id="{{ $tx->id }}" class="btn-detail inline-flex h-8 w-8 items-center justify-center rounded-lg text-text-secondary hover:bg-bg hover:text-primary transition-colors cursor-pointer" title="Detail">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                            <form method="POST" action="{{ route('admin.transactions.destroy', $tx) }}" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
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
                    <td colspan="8" class="px-4 py-10 text-center text-sm text-text-secondary">Belum ada transaksi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $transactions->links() }}
    </div>
</div>

<div x-data="{ open: false, loading: false, data: null, error: null }"
     x-show="open"
     x-cloak
     @keydown.escape.window="open = false"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4"
     @click.self="open = false">
    <div class="w-full max-w-[520px] rounded-[12px] border border-border bg-surface shadow-xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between border-b border-border px-6 py-4">
            <h3 class="font-display text-lg font-bold tracking-[-0.03em]">Detail Transaksi</h3>
            <button type="button" @click="open = false" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-text-secondary hover:bg-bg hover:text-text-primary transition-colors cursor-pointer">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <template x-if="loading">
                <div class="flex items-center justify-center py-10">
                    <div class="h-6 w-6 animate-spin rounded-full border-2 border-primary border-t-transparent"></div>
                    <span class="ml-3 text-sm text-text-secondary">Memuat...</span>
                </div>
            </template>
            <template x-if="error">
                <div class="py-10 text-center text-sm text-red-500" x-text="error"></div>
            </template>
            <template x-if="!loading && !error && data">
                <div>
                    <div class="flex items-center justify-between mb-5">
                        <span class="text-xs text-text-secondary">ID Transaksi</span>
                        <span class="font-mono text-xs" x-text="'#' + data.id"></span>
                    </div>
                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wider text-text-secondary">Produk</p>
                            <p class="mt-1 font-medium" x-text="data.product.name"></p>
                        </div>
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wider text-text-secondary">Kategori</p>
                            <p class="mt-1 text-text-secondary" x-text="data.product.category.name"></p>
                        </div>
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wider text-text-secondary">Customer</p>
                            <p class="mt-1">
                                <template x-if="data.user">
                                    <span><span class="font-medium" x-text="data.user.name"></span><span class="text-text-secondary text-xs" x-text="' (' + data.user.email + ')'"></span></span>
                                </template>
                                <template x-if="!data.user">
                                    <span class="text-text-secondary">-</span>
                                </template>
                            </p>
                        </div>
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wider text-text-secondary">Tipe</p>
                            <p class="mt-1">
                                <span x-show="data.type === 'sale'" class="inline-flex rounded-full bg-success/10 px-2.5 py-0.5 text-xs font-medium text-success">Penjualan</span>
                                <span x-show="data.type !== 'sale'" class="inline-flex rounded-full bg-warning/10 px-2.5 py-0.5 text-xs font-medium text-warning">Pembelian</span>
                            </p>
                        </div>
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wider text-text-secondary">Jumlah</p>
                            <p class="mt-1 font-mono" x-text="data.quantity"></p>
                        </div>
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wider text-text-secondary">Total</p>
                            <p class="mt-1 font-mono text-lg font-bold" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(data.total)"></p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-xs font-medium uppercase tracking-wider text-text-secondary">Tanggal</p>
                            <p class="mt-1 text-text-secondary" x-text="new Date(data.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })"></p>
                        </div>
                    </div>
                    <template x-if="data.notes">
                        <div class="border-t border-border pt-4 mt-5">
                            <p class="text-xs font-medium uppercase tracking-wider text-text-secondary">Catatan</p>
                            <p class="mt-1 text-sm text-text-secondary" x-text="data.notes"></p>
                        </div>
                    </template>
                </div>
            </template>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-detail').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            const alpineEl = document.querySelector('[x-data]');
            const alpine = Alpine.$data(alpineEl);
            alpine.open = true;
            alpine.loading = true;
            alpine.error = null;
            alpine.data = null;

            fetch('/admin/transactions/' + id, {
                headers: { 'Accept': 'application/json' }
            })
            .then(function (res) {
                if (!res.ok) throw new Error('Gagal memuat data');
                return res.json();
            })
            .then(function (d) {
                alpine.data = d;
                alpine.loading = false;
            })
            .catch(function () {
                alpine.error = 'Gagal memuat data transaksi.';
                alpine.loading = false;
            });
        });
    });
});
</script>
@endsection
