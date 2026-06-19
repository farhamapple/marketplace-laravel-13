@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')
<div class="mx-auto max-w-[640px] px-6 py-10">
    <a href="{{ route('admin.categories.index') }}" class="mb-6 inline-flex items-center gap-1 text-sm text-text-secondary hover:text-text-primary transition-colors">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
        Kembali
    </a>

    <h1 class="font-display text-[clamp(1.75rem,3vw,2rem)] font-bold tracking-[-0.03em]">Edit Kategori</h1>
    <p class="mt-1 text-sm text-text-secondary">Perbarui informasi kategori</p>

    <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="mt-8 space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="mb-1.5 block text-sm font-medium">Nama Kategori</label>
            <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" class="w-full rounded-xl border border-border bg-surface px-4 py-2.5 text-sm outline-none focus:border-primary/50 focus:ring-2 focus:ring-primary/10 transition-all" required>
            @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="description" class="mb-1.5 block text-sm font-medium">Deskripsi</label>
            <textarea name="description" id="description" rows="3" class="w-full rounded-xl border border-border bg-surface px-4 py-2.5 text-sm outline-none focus:border-primary/50 focus:ring-2 focus:ring-primary/10 transition-all">{{ old('description', $category->description) }}</textarea>
            @error('description') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="rounded-xl bg-primary px-6 py-2.5 text-sm font-medium text-white hover:bg-primary-hover transition-colors cursor-pointer">Perbarui</button>
            <a href="{{ route('admin.categories.index') }}" class="rounded-xl border border-border px-6 py-2.5 text-sm font-medium text-text-secondary hover:bg-bg transition-colors">Batal</a>
        </div>
    </form>
</div>
@endsection
