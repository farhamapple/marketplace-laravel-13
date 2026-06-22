@php
    $isMinimal = true;
@endphp

@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
<div class="flex min-h-[calc(100dvh-3.5rem)] items-center justify-center px-6 py-12">
    <div class="w-full max-w-sm">
        <div class="mb-8 text-center">
            <h1 class="font-display text-[clamp(1.75rem,4vw,2rem)] font-bold tracking-[-0.03em]">Selamat Datang</h1>
            <p class="mt-2 text-sm text-text-secondary">Masuk ke akun Anda</p>
        </div>

        <form method="POST" action="{{ url('/login') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="mb-1.5 block text-sm font-medium text-text-primary">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
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
                    autocomplete="current-password"
                    placeholder="&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;"
                    class="w-full rounded-[6px] border border-border bg-surface px-3.5 py-2.5 text-sm text-text-primary placeholder:text-neutral placeholder:transition-colors focus:border-primary focus:shadow-[0_0_0_3px_rgba(99,102,241,0.12)] focus:outline-none transition-all duration-200 @error('password') border-error @enderror"
                />
                @error('password')
                    <p class="mt-1.5 text-xs text-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <label class="flex cursor-pointer items-center gap-2">
                    <input type="checkbox" name="remember" class="size-4 rounded-full border-border text-primary focus:shadow-[0_0_0_3px_rgba(99,102,241,0.12)] focus:outline-none" />
                    <span class="text-sm text-text-secondary">Ingat saya</span>
                </label>
            </div>

            <button type="submit" class="btn btn-primary w-full h-[38px] rounded-[6px] bg-primary text-sm font-medium text-white transition-all duration-200 hover:bg-primary-hover hover:shadow-[0_4px_12px_rgba(99,102,241,0.35)] hover:-translate-y-px focus:shadow-[0_0_0_3px_rgba(99,102,241,0.12)] focus:outline-none">
                Masuk
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-text-secondary">
            Belum punya akun?
            <a href="{{ url('/register') }}" class="font-medium text-primary hover:text-primary-hover transition-colors">Daftar</a>
        </p>
    </div>
</div>
@endsection
