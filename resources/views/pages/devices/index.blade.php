@extends('layout.app')

@section('content')
<div class="p-6">
    <h2 class="text-xl font-semibold mb-4">Daftar Device</h2>

    <!-- Tombol Tambah Device -->
    @include('components.button', [
        'text' => 'Tambah Device',
        'type' => 'button',
        'variant' => 'primary',
        'size' => 'md',
        'class' => 'mt-6',
        'link' => route('devices.create')
    ])

    <!-- Container Form Tambah Area -->
    <div id="formContainer" class="hidden mt-6">
        <form id="areaForm" class="bg-white dark:bg-neutral-800 p-6 rounded-xl shadow-md">
            <input type="hidden" name="parent_id" id="parent_id">

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Nama Area</label>
                <input type="text" name="name" id="areaName"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200 p-2 dark:bg-neutral-700 dark:border-neutral-600"
                    placeholder="Masukkan nama area" required>
            </div>

            <div class="flex justify-end space-x-2">
                <button type="button" id="btnCancel"
                    class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Simpan
                </button>
            </div>
        </form>
    </div>

    <!-- List Area -->
    <div id="areaList" class="space-y-4 mt-4">
        @foreach ($devices as $device)
        <div class="p-5 border rounded-xl bg-white dark:bg-neutral-800 shadow-sm">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="font-semibold text-lg text-gray-900 dark:text-white">
                        {{ $device->device_name }}
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        {{ $device->device_type }}
                    </p>    
                </div>
                @include('components.button', [
                    'text' => 'EDIT',
                    'type' => 'button',
                    'variant' => 'primary',
                    'size' => 'sm',
                    'class' => '',
                    'id' => 'btnAddChild',
                    'link' => route('devices.edit', ['id' => $device->id])
                ])
            </div>
        </div>            
        @endforeach
    </div>
</div>
@endsection