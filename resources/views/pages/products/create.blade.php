@extends('layout.app')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-8">
    <div style="width:464px" class="mx-auto bg-white dark:bg-neutral-800 rounded-xl shadow-lg px-6 py-6">
        {{-- Title --}}
        <div class="relative w-full">
            <div>
                <a href="{{ route('products.index') }}" class="text-gray-500 absolute left-0">&lt; kembali</a>
            </div>
            <h2 class="text-2xl font-bold mb-6 text-center">Tambah Product</h2>
        </div>

        <form action="{{ route('products.store') }}" method="POST">
            @csrf

            {{-- Input Name --}}
            <div class="mb-5">
                @include('components.input', [
                    'type' => 'text',
                    'name' => 'name',
                    'id' => 'name',
                    'placeholder' => 'Nama Product',
                    'required' => true,
                    'autofocus' => true,
                    'label' => 'Nama Product',
                ])
            </div>

            {{-- Input Description --}}
            <div class="mb-5">
                <label for="description" class="block mb-2 text-sm font-medium text-black-700 dark:text-black-200">
                    Deskripsi
                </label>
                <textarea
                    name="description"
                    id="description"
                    rows="3"
                    placeholder="Deskripsi Product"
                    class="form-control ps-4 border-neutral-300 bg-neutral-50 dark:bg-dark-2 rounded-xl w-full"
                >{{ old('description') }}</textarea>
                @error('description')
                    <span style="color:red" class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- Input Price --}}
            <div class="mb-5">
                @include('components.input', [
                    'type' => 'number',
                    'name' => 'price',
                    'id' => 'price',
                    'placeholder' => 'Harga Product',
                    'required' => true,
                    'label' => 'Harga',
                    'step' => '0.01',
                    'min' => '0',
                ])
            </div>

            {{-- Select Tipe Product --}}
            <div class="mb-5">
                <label class="block mb-2 text-sm font-medium text-black-700 dark:text-black-200">
                    Tipe Product <span class="text-red-500">*</span>
                </label>

                <select
                    name="product_type_id"
                    id="product_type_id"
                    class="form-control h-[56px] ps-4 border-neutral-300 bg-neutral-50 dark:bg-dark-2 rounded-xl w-full"
                >
                    <option value="">-- Pilih Tipe Product --</option>
                    @foreach($productTypes as $type)
                        <option value="{{ $type->id }}" {{ old('product_type_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>

                <button type="button" id="btnAddType" class="text-sm text-blue-600 mt-2 hover:underline">
                    + Tambah tipe baru
                </button>

                <div id="newTypeWrap" class="mt-2 {{ old('new_product_type') ? '' : 'hidden' }}">
                    <input
                        type="text"
                        name="new_product_type"
                        id="new_product_type"
                        value="{{ old('new_product_type') }}"
                        placeholder="Nama tipe baru"
                        class="form-control h-[48px] ps-4 border-neutral-300 bg-neutral-50 dark:bg-dark-2 rounded-xl w-full"
                        {{ old('new_product_type') ? '' : 'disabled' }}
                    >
                    <button type="button" id="btnCancelType" class="text-sm text-gray-500 mt-2 hover:underline">
                        Batal, pilih dari daftar
                    </button>
                </div>

                @error('product_type_id')
                    <span style="color:red" class="text-red-500 text-sm block mt-1">{{ $message }}</span>
                @enderror
                @error('new_product_type')
                    <span style="color:red" class="text-red-500 text-sm block mt-1">{{ $message }}</span>
                @enderror
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
    const selectEl     = document.getElementById('product_type_id');
    const newTypeWrap  = document.getElementById('newTypeWrap');
    const newTypeInput = document.getElementById('new_product_type');
    const btnAddType   = document.getElementById('btnAddType');
    const btnCancelType = document.getElementById('btnCancelType');

    function showNewType() {
        selectEl.classList.add('hidden');
        selectEl.disabled = true;
        btnAddType.classList.add('hidden');
        newTypeWrap.classList.remove('hidden');
        newTypeInput.disabled = false;
        newTypeInput.focus();
    }

    function showSelect() {
        selectEl.classList.remove('hidden');
        selectEl.disabled = false;
        btnAddType.classList.remove('hidden');
        newTypeWrap.classList.add('hidden');
        newTypeInput.disabled = true;
        newTypeInput.value = '';
    }

    btnAddType.addEventListener('click', showNewType);
    btnCancelType.addEventListener('click', showSelect);

    @if(old('new_product_type'))
        showNewType();
    @endif
});
</script>
@endsection
