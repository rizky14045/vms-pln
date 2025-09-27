<div class="p-4 border rounded-xl bg-white dark:bg-neutral-800 shadow-sm">
    <div class="flex justify-between items-center">
        <h3 class="font-semibold text-lg text-gray-900 dark:text-white">
            {{ $area->name }}
        </h3>

        <div class="flex items-center gap-2">
            {{-- Tombol tambah child --}}
            {{-- @include('components.button', [
                'text' => '+',
                'type' => 'button',
                'variant' => 'primary',
                'size' => 'sm',
                'id' => 'btnAddChild',
                'class' => 'px-4 py-2 bg-yellow-500 rounded-lg shadow-lg',
                'link' => route('areas.create', ['parent' => $area->id])
            ]) --}}

            {{-- Tombol edit --}}
            @include('components.button', [
                'text' => 'Edit',
                'type' => 'button',
                'variant' => 'secondary',
                'size' => 'sm',
                'link' => route('areas.edit', $area->id)
            ])
            {{-- Tombol expand/collapse hanya kalau punya anak --}}
            @if ($area->childrenArea && $area->childrenArea->count())
                <button class="btnToggle px-2 py-1 text-sm rounded-md bg-gray-200 dark:bg-neutral-700 text-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-neutral-600"
                        aria-expanded="false">
                    ▼
                </button>
            @endif
            @if ($area->devices && $area->devices->count())
                <button class="btnToggle px-2 py-1 text-sm rounded-md bg-gray-200 dark:bg-neutral-700 text-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-neutral-600"
                        aria-expanded="false">
                    ▼
                </button>
            @endif
        </div>
    </div>

    {{-- Children --}}
    @if ($area->childrenArea && $area->childrenArea->count())
        <div class="children ml-6 mt-3 space-y-3 border-l pl-4 border-gray-300 dark:border-neutral-600 hidden">
            @foreach ($area->childrenArea as $child)
                @include('components.area-item', ['area' => $child])
            @endforeach
        </div>
    @endif

    @if ($area->devices && $area->devices->count())
        <div class="children ml-6 mt-3 space-y-3 border-l pl-4 border-gray-300 dark:border-neutral-600 hidden border rounded-lg p-4">
            <h3 class="font-semibold text-md text-gray-900 dark:text-white" style="font-size: 16px">
                List Device
            </h3>
            @foreach ($area->devices as $child)
                <h3 class="text-gray-900 dark:text-white" style="font-size: 14px">
                    {{ $child->device_name }}
                </h3>
            @endforeach
        </div>
    @endif
</div>
