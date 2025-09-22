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
        <label for="{{ $id }}" class="block mb-2 text-sm font-medium text-black-700 dark:text-black-200">
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
        <textarea 
            name="{{ $name }}"
            id="{{ $id }}"
            {{ $attributes->merge([
                'class' => 'form-control ps-11 h-[90px] border-neutral-300 bg-neutral-50 dark:bg-dark-2 rounded-xl w-full'
            ]) }}
        >
                {{ old($name) }}
        </textarea>
    </div>

    {{-- Error message --}}
    @error($name)
        <span style="color:red" class="text-red-500 absolute text-sm">{{ $message }}</span>
    @enderror
</div>
