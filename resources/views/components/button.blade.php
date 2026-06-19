@props([
    'variant' => 'primary',
    'size' => 'medium',
    'href' => null,
])

@php
$base = 'inline-flex items-center justify-center font-medium rounded-[6px] transition-all duration-200 hover:-translate-y-[1px] focus:outline-none focus:shadow-[0_0_0_3px_rgba(99,102,241,0.12)]';

$variants = [
    'primary' => 'bg-primary text-white hover:bg-primary-hover hover:shadow-[0_4px_12px_rgba(99,102,241,0.35)]',
    'secondary' => 'bg-transparent border border-border text-text-primary hover:border-text-secondary',
    'ghost' => 'bg-transparent text-text-secondary hover:text-text-primary',
    'destructive' => 'bg-transparent border border-error text-error hover:bg-error/5',
];

$sizes = [
    'small' => 'h-8 px-3 text-sm gap-1.5',
    'medium' => 'h-[38px] px-4 text-sm gap-2',
    'large' => 'h-11 px-6 text-base gap-2',
];
@endphp

@if ($href)
    <a href="{{ $href }}" class="{{ $base }} {{ $variants[$variant] }} {{ $sizes[$size] }}" {{ $attributes }}>
        {{ $slot }}
    </a>
@else
    <button class="{{ $base }} {{ $variants[$variant] }} {{ $sizes[$size] }}" {{ $attributes }}>
        {{ $slot }}
    </button>
@endif
