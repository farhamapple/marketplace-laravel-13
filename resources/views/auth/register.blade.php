@extends('layouts.app')

@section('title', 'Daftar')

@section('content')
<div class="flex min-h-[calc(100dvh-3.5rem)] items-center justify-center px-6 py-12">
    <div class="w-full max-w-sm">
        <div class="mb-8 text-center">
            <h1 class="font-display text-[clamp(1.75rem,4vw,2rem)] font-bold tracking-[-0.03em]">Buat Akun Baru</h1>
            <p class="mt-2 text-sm text-text-secondary">Daftar untuk mulai berbelanja</p>
        </div>

        <form method="POST" action="{{ url('/register') }}" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="mb-1.5 block text-sm font-medium text-text-primary">Nama</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="Your name"
                    class="w-full rounded-[6px] border border-border bg-surface px-3.5 py-2.5 text-sm text-text-primary placeholder:text-neutral placeholder:transition-colors focus:border-primary focus:shadow-[0_0_0_3px_rgba(99,102,241,0.12)] focus:outline-none transition-all duration-200 @error('name') border-error @enderror"
                />
                @error('name')
                    <p class="mt-1.5 text-xs text-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="mb-1.5 block text-sm font-medium text-text-primary">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="email"
                    placeholder="you@example.com"
                    class="w-full rounded-[6px] border border-border bg-surface px-3.5 py-2.5 text-sm text-text-primary placeholder:text-neutral placeholder:transition-colors focus:border-primary focus:shadow-[0_0_0_3px_rgba(99,102,241,0.12)] focus:outline-none transition-all duration-200 @error('email') border-error @enderror"
                />
                @error('email')
                    <p class="mt-1.5 text-xs text-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="mb-1.5 block text-sm font-medium text-text-primary">Kata Sandi</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    placeholder="Min. 8 karakter"
                    class="w-full rounded-[6px] border border-border bg-surface px-3.5 py-2.5 text-sm text-text-primary placeholder:text-neutral placeholder:transition-colors focus:border-primary focus:shadow-[0_0_0_3px_rgba(99,102,241,0.12)] focus:outline-none transition-all duration-200 @error('password') border-error @enderror"
                />
                @error('password')
                    <p class="mt-1.5 text-xs text-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="mb-1.5 block text-sm font-medium text-text-primary">Konfirmasi Kata Sandi</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="Ulangi kata sandi"
                    class="w-full rounded-[6px] border border-border bg-surface px-3.5 py-2.5 text-sm text-text-primary placeholder:text-neutral placeholder:transition-colors focus:border-primary focus:shadow-[0_0_0_3px_rgba(99,102,241,0.12)] focus:outline-none transition-all duration-200"
                />
            </div>

            <button type="submit" class="btn btn-primary w-full h-[38px] rounded-[6px] bg-primary text-sm font-medium text-white transition-all duration-200 hover:bg-primary-hover hover:shadow-[0_4px_12px_rgba(99,102,241,0.35)] hover:-translate-y-px focus:shadow-[0_0_0_3px_rgba(99,102,241,0.12)] focus:outline-none">
                Daftar
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-text-secondary">
            Sudah punya akun?
            <a href="{{ url('/login') }}" class="font-medium text-primary hover:text-primary-hover transition-colors">Masuk</a>
        </p>
    </div>
</div>
@endsection
