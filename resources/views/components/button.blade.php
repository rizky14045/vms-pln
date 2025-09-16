@props([
    'text' => 'Button',
    'type' => 'submit',
    'variant' => 'primary', // default warna
    'size' => 'md', // md, sm, lg
    'class' => '',
    'scripts' => ''
])

@php
    $baseClasses = "btn justify-center rounded-xl font-medium";
    $variantClasses = match($variant) {
        'primary' => 'btn-primary',
        'secondary' => 'btn-secondary',
        'danger' => 'btn-danger',
        default => 'btn-primary',
    };

    $sizeClasses = match($size) {
        'sm' => 'text-sm btn-sm px-3 py-2',
        'lg' => 'text-lg px-6 py-4',
        default => 'text-sm btn-sm px-3 py-4', // md
    };
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => "$baseClasses $variantClasses $sizeClasses $class"]) }}>
    {{ $text }}
</button>
