@extends('layout.app')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-8">
    <div class="w-1/2 mx-auto bg-white dark:bg-neutral-800 rounded-xl shadow-lg px-6 py-6">

        {{-- Title --}}
        <div class="relative w-full">

            <h2 class="text-2xl font-bold mb-6 text-center">Detail Registrasi Karyawan</h2>
        </div>

        <form action="{{ route('devices.store') }}" method="POST">
            @csrf

            {{-- Input Nama Device --}}
            <div class="mb-5">
                @include('components.input', [
                    'type' => 'text',
                    'name' => 'device_name',
                    'id' => 'device_name',
                    'placeholder' => 'Nama Device',
                    'required' => true,
                    'autofocus' => true,
                    'label' => 'Nama Device',
                ])
            </div>

            {{-- Pilih Area --}}
            <div class="mb-5">
                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                    Pilih Area
                </label>
                
                <div class="space-y-2 border rounded-lg p-4 max-h-72 overflow-y-auto bg-neutral-50 dark:bg-dark-2">
                    {{-- Rekursif tampilkan area sebagai checkbox --}}
                    @php
                        function renderAreaCheckbox($areas, $prefix = '', $level = 0) {
                            foreach ($areas as $area) {
                                echo '<div class="flex items-center space-x-2" style="margin-left:'.($level*12).'px">';
                                echo '<input type="checkbox" id="area_'.$area->id.'" name="area_ids[]" value="'.$area->id.'" data-parent="'.($area->parent_id ?? '').'" class="area-checkbox w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">';
                                echo '<label for="area_'.$area->id.'" class="text-gray-700 dark:text-gray-200 cursor-pointer">'.$prefix.$area->name.'</label>';
                                echo '</div>';

                                if ($area->childrenArea && $area->childrenArea->count()) {
                                    renderAreaCheckbox($area->childrenArea, '', $level+1);
                                }
                            }
                        }
                        renderAreaCheckbox($areas);
                    @endphp
                </div>
            </div>

            {{-- Button --}}
            <div class="flex justify-end">
                @include('components.button', [
                    'text' => 'Simpan',
                    'type' => 'submit',
                    'variant' => 'primary',
                    'size' => 'md'
                ])
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkboxes = document.querySelectorAll('.area-checkbox');
        const form = document.querySelector('form');

        checkboxes.forEach(cb => {
            cb.addEventListener('change', function () {
                const isChecked = cb.checked;
                let parentId = cb.getAttribute('data-parent') || null;

                if (isChecked) {
                    // ✅ Jika dicentang -> semua ancestor harus dicentang
                    while (parentId) {
                        const parentEl = document.querySelector(`#area_${parentId}`);
                        if (!parentEl) break;
                        if (!parentEl.checked) parentEl.checked = true;
                        parentId = parentEl.getAttribute('data-parent') || null;
                    }
                } else {
                    // ❌ Jika di-uncheck -> semua descendant ikut di-uncheck
                    uncheckChildren(cb.value);
                }
            });
        });

        function uncheckChildren(parentId) {
            const children = document.querySelectorAll(
                `.area-checkbox[data-parent="${parentId}"]`
            );

            children.forEach(child => {
                if (child.checked) {
                    child.checked = false;
                    // rekursif ke bawah
                    uncheckChildren(child.value);
                }
            });
        }

        // 🔥 Pastikan semua checkbox checked terkirim saat submit
        form.addEventListener('submit', function () {
            checkboxes.forEach(cb => {
                if (cb.checked) {
                    cb.disabled = false; // pastikan tidak disabled
                }
            });
        });
    });
</script>


@endsection
