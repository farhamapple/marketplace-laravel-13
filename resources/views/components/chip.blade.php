@props([
    'active' => false,
    'color' => null,
])

@php
$classes = 'inline-flex items-center rounded-full px-3 py-1 text-xs font-medium transition-colors';

if ($color === 'success') {
    $classes .= ' bg-success/10 text-success';
} elseif ($color === 'warning') {
    $classes .= ' bg-warning/10 text-warning';
} elseif ($color === 'error') {
    $classes .= ' bg-error/10 text-error';
} elseif ($active) {
    $classes .= ' bg-primary text-white';
} else {
    $classes .= ' bg-neutral/10 text-text-secondary hover:bg-neutral/20';
}
@endphp

<span class="{{ $classes }}" {{ $attributes }}>
    {{ $slot }}
</span>
