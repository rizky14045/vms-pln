@extends('layout.app') {{-- ganti dengan layout kamu --}}

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-8">
    <div style="width:464px" class="mx-auto bg-white dark:bg-neutral-800 rounded-xl shadow-lg px-6 py-6">
        {{-- Title --}}
        
        <h2 class="text-2xl font-bold mb-6 text-center">Tambah Area</h2>

        <form action="{{ route('areas.store') }}" method="POST">
            @csrf

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
