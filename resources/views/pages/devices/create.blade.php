@extends('layout.app')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-8">
    <div style="width:520px" class="mx-auto bg-white dark:bg-neutral-800 rounded-xl shadow-lg px-6 py-6">

        {{-- Title --}}
        <div class="relative w-full">
            <div>
                <a href="{{ route('devices.index') }}" 
                   class="text-gray-500 absolute left-0 hover:text-gray-700 dark:hover:text-gray-300 transition">
                   &lt; kembali
                </a>
            </div>
            <h2 class="text-2xl font-bold mb-6 text-center">Tambah Device</h2>
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

            {{-- Input select type device (controller / FR) --}}
            <div class="mb-5">
                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                    Pilih Area
                </label>
                
                <div class="space-y-2 border rounded-lg p-4 max-h-72 overflow-y-auto bg-neutral-50 dark:bg-dark-2">
                    <div class="flex items-center mb-4">
                        <input id="device-type-1" type="radio" value="Controller" name="device_type" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="device-type-1" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Controller</label>
                    </div>
                    <div class="flex items-center mb-4">
                        <input id="device-type-2" type="radio" value="FR" name="device_type" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="device-type-2" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">FR</label>
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

@endsection
