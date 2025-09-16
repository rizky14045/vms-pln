{{-- Default properties --}}
@props([
    'type' => 'text',
    'icon' => null,
    'placeholder' => '',
    'name',
    'id' => $name,
    'label' => null,
])

<div class="icon-field mb-6 relative">
    {{-- Label --}}
    @if($label)
        <label for="{{ $id }}" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
            {{ $label }}
        </label>
    @endif

    <div class="relative">
        {{-- Icon --}}
        @if($icon)
            <span class="absolute start-4 top-1/2 -translate-y-1/2 pointer-events-none flex text-xl">
                <iconify-icon icon="{{ $icon }}"></iconify-icon>
            </span>
        @endif

        {{-- Input --}}
        <input 
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $id }}"
            value="{{ old($name) }}"
            placeholder="{{ $placeholder }}"
            {{ $attributes->merge([
                'class' => 'form-control h-[56px] ps-11 border-neutral-300 bg-neutral-50 dark:bg-dark-2 rounded-xl w-full'
            ]) }}
        >

        {{-- Toggle password if type=password --}}
        @if($type === 'password')
            <span 
                class="toggle-password ri-eye-line cursor-pointer absolute end-0 top-1/2 -translate-y-1/2 me-4 text-secondary-light"
                data-toggle="#{{ $id }}">
            </span>
        @endif
    </div>

    {{-- Error message --}}
    @error($name)
        <span style="color:red" class="text-red-500 absolute text-sm">{{ $message }}</span>
    @enderror
</div>
