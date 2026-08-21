@extends('layout.app')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-8">
    <div class="w-1/2 mx-auto bg-white dark:bg-neutral-800 rounded-xl shadow-lg px-6 py-6">

        {{-- Title --}}
        <div class="relative w-full">

            <h2 class="text-2xl font-bold mb-6 text-center">Edit Visitor ke Karyawan</h2>
        </div>

        <form action="{{ route('registered.update-card-visitor',['id'=>$registeredPerson->id]) }}" method="POST">
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

            <div class="flex flex-col items-center justify-center gap-4">
                <h2 class="text-lg font-semibold mb-2">Upload Foto (Opsional)</h2>

                <!-- Preview Gambar -->
                <img
                id="imagePreview"
                class="w-full max-w-[150px] h-auto border rounded-lg shadow hidden"
                alt="Preview Gambar"
                />

                <!-- Input File -->
                <input
                type="file"
                id="fileInput"
                name="person_image"
                accept="image/*"
                class="hidden"
                />

                <button
                type="button"
                onclick="document.getElementById('fileInput').click()"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
                >
                Pilih Gambar 
                </button>

                <div class="text-gray-600 text-sm text-center max-w-xs">
                Pastikan wajah terlihat jelas dan tidak buram.<br>
                Format yang didukung: JPG, JPEG, PNG.<br>
                Resolusi minimal: <span class="font-semibold">600×800 px</span>.<br>
                Resolusi maksimal: <span class="font-semibold">1920x1080 px</span>.<br>
                Ukuran maksimal file: <span class="font-semibold">2 MB</span>.
                </div>
                
                @if($errors->has('person_image'))
                <div class="text-red-500 text-sm">
                    {{ $errors->first('person_image') }}
                </div>
                @endif
            </div>

            {{-- Button --}}
            <div class="flex justify-end gap-3">
                @include('components.button', [
                    'text' => 'Kembali',
                    'variant' => 'success',
                    'size' => 'md',
                    'link' => route('registered.index.visitor'),
                    'class' => 'bg-success-600 hover:bg-success-700',
                    ])
                @include('components.button', [
                    'text' => 'Save',
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

<script>
    const fileInput = document.getElementById("fileInput");
    const imagePreview = document.getElementById("imagePreview");

    fileInput.addEventListener("change", (event) => {
        const file = event.target.files[0];
        if (file && file.type.startsWith("image/")) {
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.src = e.target.result;
            imagePreview.classList.remove("hidden");
        };
        reader.readAsDataURL(file);
        } else {
        alert("Silakan pilih file gambar yang valid.");
        }
    });
</script>


@endsection
