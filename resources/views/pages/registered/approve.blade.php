@extends('layout.app')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-8">
    <div class="w-1/2 mx-auto bg-white dark:bg-neutral-800 rounded-xl shadow-lg px-6 py-6">

        {{-- Title --}}
        <div class="relative w-full">

            <h2 class="text-2xl font-bold mb-6 text-center">Approve Registrasi Karyawan</h2>
        </div>

        <form action="{{ route('registered.update-approve',['id'=>$registeredPerson->id]) }}" method="POST">
            @csrf
            @method('PATCH')

            {{-- Input Nama Device --}}
            <div class="mb-5">
                @include('components.input', [
                    'type' => 'text',
                    'name' => 'name',
                    'id' => 'name',
                    'placeholder' => 'Nama Karyawan',
                    'required' => true,
                    'autofocus' => true,
                    'label' => 'Nama Karyawan',
                    'value' => $registeredPerson->user->name ?? ''
                ])
            </div>
            <div class="mb-5">
                @include('components.input', [
                    'type' => 'text',
                    'name' => 'nid',
                    'id' => 'nid',
                    'placeholder' => 'NID Karyawan',
                    'required' => true,
                    'autofocus' => true,
                    'label' => 'NID Karyawan',
                    'value' => $registeredPerson->user->nid ?? ''
                ])
            </div>

            {{-- Pilih Area --}}
            <div class="mb-5">
                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                    Pilih Area
                </label>
                
                <div class="space-y-2 border rounded-lg p-4 max-h-72 overflow-y-auto bg-neutral-50 dark:bg-dark-2">
                    @foreach ($areas as $area)
                        <div class="flex items-center mb-4">
                            <input id="area-{{$area->id}}" type="radio" value="{{$area->id}}" name="area_id" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="area-{{$area->id}}" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{$area->name}}</label>
                        </div>

                    @endforeach
                </div>
            </div>

            {{-- Button --}}
            <div class="flex justify-end gap-3">
                @include('components.button', [
                    'text' => 'Kembali',
                    'variant' => 'success',
                    'size' => 'md',
                    'link' => route('registered.index'),
                    'class' => 'bg-success-600 hover:bg-success-700',
                    ])
                @include('components.button', [
                    'text' => 'Reject',
                    'type' => 'submit',
                    'variant' => 'primary',
                    'size' => 'md',
                    'class' => 'bg-danger-600 hover:bg-danger-700',
                    'value' => 'reject',
                    'name' => 'action'
                    ])
                @include('components.button', [
                    'text' => 'Approve',
                    'type' => 'submit',
                    'variant' => 'primary',
                    'size' => 'md',
                    'value' => 'approve',
                    'name' => 'action'
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
