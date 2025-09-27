@extends('layout.app') {{-- ganti dengan layout kamu --}}

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-8">
    <div style="width:464px" class="mx-auto bg-white dark:bg-neutral-800 rounded-xl shadow-lg px-6 py-6">
        {{-- Title --}}
        <div class="relative w-full">
            <div>
                <a href="{{ route('areas.index') }}" class="text-gray-500 absolute left-0">< kembali</a>
            </div>
            <h2 class="text-2xl font-bold mb-6 text-center">Tambah Area</h2>
        </div>

        <form action="{{ route('areas.store') }}" method="POST">
            @csrf

            {{-- Hidden parent_id --}}
            <input type="hidden" name="parent_id" value="{{ request('parent') }}">

            {{-- Input Name --}}
            <div class="mb-5">
                @include('components.input', [
                    'type' => 'text',
                    'name' => 'name',
                    'id' => 'name',
                    'placeholder' => 'Nama Area',
                    'required' => true,
                    'autofocus' => true,
                    'label' => 'Nama Area',
                ])
            </div>

            {{-- Input Description --}}
            <div class="mb-5">
                @include('components.input', [
                    'type' => 'text',
                    'name' => 'description',
                    'id' => 'description',
                    'placeholder' => 'Deskripsi Area',
                    'required' => true,
                    'autofocus' => true,
                    'label' => 'Deskripsi Area',
                ])
            </div>

            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <label class="block text-sm font-medium text-black-700 dark:text-black-200">
                        Pilih Device
                    </label>
                </div>

                <div 
                    id="deviceList"
                    class="overflow-hidden h-0 transition-all duration-300 ease-in-out"
                >
                    <div class="space-y-2 mt-2">
                        @foreach($devices as $device)
                            <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-neutral-700">
                                <input
                                    type="checkbox"
                                    name="device_ids[]"
                                    value="{{ $device->id }}"
                                    id="device_{{ $device->id }}"
                                    {{ in_array($device->id, old('device_ids', $selected ?? [])) ? 'checked' : '' }}
                                    class="device-checkbox w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                >
                                <div class="flex-1">
                                    <div class="text-sm font-medium text-gray-800 dark:text-gray-100">
                                        {{ $device->device_name }}
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
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
