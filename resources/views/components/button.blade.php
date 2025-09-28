@props([
    'text' => 'Button',
    'type' => 'submit',
    'variant' => 'primary', // default warna
    'size' => 'md', // md, sm, lg
    'class' => '',
    'scripts' => '',
    'link' => null, // tambahan untuk URL,
    'value' => null,
    'name' => null,
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

    $allClasses = "$baseClasses $variantClasses $sizeClasses $class";
@endphp

@if ($link)
    <a href="{{ $link }}" {{ $attributes->merge(['class' => $allClasses]) }}>
        {{ $text }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $allClasses]) }}
        @if ($scripts) onclick="{{ $scripts }}" @endif @if ($value) value="{{ $value }}" @endif @if ($name) name="{{ $name }}" @endif>
        {{ $text }}
    </button>
@endif
