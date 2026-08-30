@extends('layout.app')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-8">
    <div class="w-1/2 mx-auto bg-white dark:bg-neutral-800 rounded-xl shadow-lg px-6 py-6">

        {{-- Title --}}
        <div class="relative w-full">
            <h2 class="text-2xl font-bold mb-6 text-center">Tambah Visitor</h2>
        </div>

        <form action="{{ route('registered.store-visitor') }}" method="POST" enctype="multipart/form-data">
            @csrf
            {{-- Input Nama Device --}}
            <div class="mb-5">
                @include('components.input', [
                    'type' => 'text',
                    'name' => 'name',
                    'id' => 'name',
                    'placeholder' => 'Nama Visitor',
                    'required' => true,
                    'autofocus' => true,
                    'label' => 'Nama Visitor',
                    'value' => $registeredPerson->user->name ?? ''
                ])
            </div>
            <div class="mb-5">
                @include('components.input', [
                    'type' => 'text',
                    'name' => 'nid',
                    'id' => 'nid',
                    'placeholder' => 'NID Visitor',
                    'required' => true,
                    'autofocus' => true,
                    'label' => 'NID Visitor',
                    'value' => $registeredPerson->user->nid ?? ''
                ])
            </div>
            <div class="mb-5">
                @include('components.input', [
                    'type' => 'email',
                    'name' => 'email',
                    'id' => 'email',
                    'placeholder' => 'Email Visitor',
                    'required' => true,
                    'label' => 'Email Visitor',
                    'value' => $registeredPerson->user->email ?? ''
                ])
            </div>
            <div>
                @include('components.input', [
                    'type' => 'text',
                    'name' => 'phone',
                    'id' => 'phone',
                    'placeholder' => '628xxxxxxx',
                    'required' => true,
                    'autofocus' => true,
                    'label' => 'Nomor HP',
                ])
            </div>
            <div class="mb-5">
                @include('components.input', [
                    'type' => 'text',
                    'name' => 'company',
                    'id' => 'company',
                    'placeholder' => 'Perusahaan',
                    'required' => true,
                    'autofocus' => true,
                    'label' => 'Perusahaan',
                    'value' => $registeredPerson->user->company ?? ''
                ])
            </div>

            <div class="mb-5">
                @include('components.input', [
                    'type' => 'text',
                    'name' => 'purpose_of_visit',
                    'id' => 'purpose_of_visit',
                    'placeholder' => 'Alasan Visit',
                    'required' => true,
                    'autofocus' => true,
                    'label' => 'Alasan Visit',
                    'value' => $registeredPerson->purpose_of_visit ?? ''
                ])
            </div>

            <div class="mb-5">
                @include('components.input', [
                    'type' => 'text',
                    'name' => 'pic_name',
                    'id' => 'pic_name',
                    'placeholder' => 'Nama PIC',
                    'required' => true,
                    'autofocus' => true,
                    'label' => 'Nama PIC',
                    'value' => $registeredPerson->pic_name ?? ''
                ])
            </div>

            <div class="mb-5">
                @include('components.input', [
                    'type' => 'date',
                    'name' => 'expired_at',
                    'id' => 'expired_at',
                    'placeholder' => 'Tanggal Expired',
                    'required' => true,
                    'autofocus' => true,
                    'label' => 'Tanggal Expired'
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
                            <input id="area-{{$area->id}}" type="radio" value="{{$area->id}}" name="area_id" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" {{ old('area_id') == $area->id ? 'checked' : '' }}>
                            <label for="area-{{$area->id}}" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{$area->name}}</label>
                        </div>

                    @endforeach
                </div>
            </div>

            <div>
                @error('area_id')
                    <span style="color:red" class="text-red-500 absolute text-sm mb-4">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex flex-col items-center justify-center gap-4">
                <h2 class="text-lg font-semibold mb-2">Upload Foto</h2>

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
                    'text' => 'Submit',
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
