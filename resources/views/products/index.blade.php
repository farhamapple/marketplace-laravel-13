@extends('layouts.app')

@section('title', 'Produk')

@section('content')
<div class="mx-auto max-w-[1280px] px-6 py-10">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="font-display text-[clamp(1.75rem,3vw,2rem)] font-bold tracking-[-0.03em]">Produk</h1>
            <p class="mt-1 text-sm text-text-secondary">Kelola produk</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-medium">+ Tambah Produk</a>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-xl border border-success/20 bg-success/5 px-4 py-3 text-sm text-success">{{ session('success') }}</div>
    @endif

    <div class="overflow-hidden rounded-[12px] border border-border bg-surface">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-border bg-bg">
                    <th class="px-4 py-3 text-left font-medium text-text-secondary">Nama</th>
                    <th class="px-4 py-3 text-left font-medium text-text-secondary">Kategori</th>
                    <th class="px-4 py-3 text-right font-medium text-text-secondary">Harga</th>
                    <th class="px-4 py-3 text-right font-medium text-text-secondary">Stok</th>
                    <th class="px-4 py-3 text-right font-medium text-text-secondary">Terjual</th>
                    <th class="px-4 py-3 text-right font-medium text-text-secondary">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                <tr class="border-b border-border last:border-0 hover:bg-bg/50 transition-colors">
                    <td class="px-4 py-3 font-medium">{{ $product->name }}</td>
                    <td class="px-4 py-3">
                        <span class="inline-flex rounded-full bg-neutral/10 px-2.5 py-0.5 text-xs font-medium text-text-secondary">{{ $product->category->name }}</span>
                    </td>
                    <td class="px-4 py-3 text-right font-mono text-sm">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-right">{{ $product->stock }}</td>
                    <td class="px-4 py-3 text-right">{{ $product->sold }}</td>
                    <td class="px-4 py-3 text-right">
                        <div class="inline-flex items-center gap-2">
                            <a href="{{ route('admin.products.edit', $product) }}" class="rounded-lg px-3 py-1.5 text-xs font-medium text-primary hover:bg-primary/5 transition-colors">Edit</a>
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-lg px-3 py-1.5 text-xs font-medium text-red-500 hover:bg-red-500/5 transition-colors cursor-pointer">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-10 text-center text-sm text-text-secondary">Belum ada produk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
