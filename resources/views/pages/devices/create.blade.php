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
