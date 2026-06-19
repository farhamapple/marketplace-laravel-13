@extends('layouts.app')

@section('title', 'Kategori')

@section('content')
<div class="mx-auto max-w-[1280px] px-6 py-10">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="font-display text-[clamp(1.75rem,3vw,2rem)] font-bold tracking-[-0.03em]">Kategori</h1>
            <p class="mt-1 text-sm text-text-secondary">Kelola kategori produk</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-medium">+ Tambah Kategori</a>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-xl border border-success/20 bg-success/5 px-4 py-3 text-sm text-success">{{ session('success') }}</div>
    @endif

    <div class="overflow-hidden rounded-[12px] border border-border bg-surface">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-border bg-bg">
                    <th class="px-4 py-3 text-left font-medium text-text-secondary">Nama</th>
                    <th class="px-4 py-3 text-left font-medium text-text-secondary">Slug</th>
                    <th class="px-4 py-3 text-left font-medium text-text-secondary">Deskripsi</th>
                    <th class="px-4 py-3 text-center font-medium text-text-secondary">Produk</th>
                    <th class="px-4 py-3 text-right font-medium text-text-secondary">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                <tr class="border-b border-border last:border-0 hover:bg-bg/50 transition-colors">
                    <td class="px-4 py-3 font-medium">{{ $category->name }}</td>
                    <td class="px-4 py-3 text-text-secondary font-mono text-xs">{{ $category->slug }}</td>
                    <td class="px-4 py-3 text-text-secondary">{{ $category->description ?? '-' }}</td>
                    <td class="px-4 py-3 text-center">{{ $category->products_count }}</td>
                    <td class="px-4 py-3 text-right">
                        <div class="inline-flex items-center gap-2">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="rounded-lg px-3 py-1.5 text-xs font-medium text-primary hover:bg-primary/5 transition-colors">Edit</a>
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-lg px-3 py-1.5 text-xs font-medium text-red-500 hover:bg-red-500/5 transition-colors cursor-pointer">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-10 text-center text-sm text-text-secondary">Belum ada kategori.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
