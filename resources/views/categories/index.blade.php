@extends('layouts.app')

@section('title', 'Kategori')

@section('content')
<div class="mx-auto max-w-[1280px] px-6 py-10">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="font-display text-[clamp(1.75rem,3vw,2rem)] font-bold tracking-[-0.03em]">Kategori</h1>
            <p class="mt-1 text-sm text-text-secondary">Kelola kategori produk</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-primary px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-primary/25 hover:bg-primary-hover hover:shadow-xl hover:shadow-primary/30 transition-all duration-200">+ Tambah Kategori</a>
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
                        <div class="inline-flex items-center gap-1">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-text-secondary hover:bg-primary/5 hover:text-primary transition-colors" title="Edit">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.85 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                            </a>
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
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
                    <td colspan="5" class="px-4 py-10 text-center text-sm text-text-secondary">Belum ada kategori.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $categories->links() }}
    </div>
</div>
@endsection
