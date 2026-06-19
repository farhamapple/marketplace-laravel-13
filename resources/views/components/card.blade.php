@props([
    'padding' => true,
    'hover' => false,
])

@php
$classes = 'bg-surface border border-border rounded-[12px] overflow-hidden transition-all duration-200';
if ($hover) {
    $classes .= ' hover:-translate-y-0.5 hover:shadow-[0_8px_30px_rgba(0,0,0,0.08)]';
}
@endphp

<div class="{{ $classes }}" {{ $attributes }}>
    @if ($padding)
        <div class="p-6">
            {{ $slot }}
        </div>
    @else
        {{ $slot }}
    @endif
</div>
