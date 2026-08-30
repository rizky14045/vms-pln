@extends('layout.app')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-8">
    <div style="width:464px" class="mx-auto bg-white dark:bg-neutral-800 rounded-xl shadow-lg px-6 py-6">
        <div class="relative w-full">
            <div>
                <a href="{{ route('visitor-cards.index') }}" class="text-gray-500 absolute left-0">&lt; kembali</a>
            </div>
            <h2 class="text-2xl font-bold mb-6 text-center">Tambah Kartu Visitor</h2>
        </div>

        <form action="{{ route('visitor-cards.store') }}" method="POST">
            @csrf

            <div class="mb-5">
                @include('components.input', [
                    'type' => 'text',
                    'name' => 'card_number',
                    'id' => 'card_number',
                    'placeholder' => 'Nomor Kartu',
                    'required' => true,
                    'autofocus' => true,
                    'label' => 'Nomor Kartu',
                ])
            </div>

            <div class="mb-5">
                <label class="block mb-2 text-sm font-medium text-black-700 dark:text-black-200">
                    Status <span class="text-red-500">*</span>
                </label>
                <select
                    name="status"
                    id="status"
                    class="form-control h-[56px] ps-4 border-neutral-300 bg-neutral-50 dark:bg-dark-2 rounded-xl w-full"
                >
                    <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Aktif / Tersedia</option>
                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Tidak Aktif / Dipakai</option>
                </select>
                @error('status')
                    <span style="color:red" class="text-red-500 text-sm block mt-1">{{ $message }}</span>
                @enderror
            </div>

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
@endsection
