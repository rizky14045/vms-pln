@extends('layout.app')

@section('content')
<div class="p-6">
    <h2 class="text-xl font-semibold mb-4">Daftar Product</h2>

    <!-- Tombol Tambah & Export Product -->
    <div class="flex gap-3 mt-6 mb-4">
        @include('components.button', [
            'text' => 'Tambah Product',
            'type' => 'button',
            'variant' => 'primary',
            'size' => 'md',
            'link' => route('products.create')
        ])
        @include('components.button', [
            'text' => 'Export Excel',
            'type' => 'button',
            'variant' => 'secondary',
            'size' => 'md',
            'link' => route('products.export', request()->query())
        ])
    </div>

    <!-- List Product -->
    <div class="space-y-4 mt-4">
        @forelse ($products as $product)
            <div class="p-5 border rounded-xl bg-white dark:bg-neutral-800 shadow-sm">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="font-semibold text-lg text-gray-900 dark:text-white">
                            {{ $product->name }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            {{ $product->description }}
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                            <span class="font-medium">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            &middot;
                            <span class="inline-block px-2 py-0.5 rounded-full bg-neutral-200 dark:bg-neutral-700 text-xs">
                                {{ $product->productType->name ?? '-' }}
                            </span>
                        </p>
                    </div>
                    @include('components.button', [
                        'text' => 'EDIT',
                        'type' => 'button',
                        'variant' => 'primary',
                        'size' => 'sm',
                        'link' => route('products.edit', ['id' => $product->id])
                    ])
                </div>
            </div>
        @empty
            <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada product.</p>
        @endforelse
    </div>
</div>
@endsection
