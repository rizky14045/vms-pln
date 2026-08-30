@extends('layout.app')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-8">
    <div class="w-1/2 mx-auto bg-white dark:bg-neutral-800 rounded-xl shadow-lg px-6 py-6">

        {{-- Title --}}
        <div class="relative w-full">

            <h2 class="text-2xl font-bold mb-6 text-center">Approve Registrasi Visitor</h2>
        </div>

        <form action="{{ route('registered.update-approve-visitor',['id'=>$registeredPerson->id, 'is_employee' => 0]) }}" method="POST">
            @csrf
            @method('PATCH')

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
                    'type' => 'text',
                    'name' => 'pic_phone',
                    'id' => 'pic_phone',
                    'placeholder' => 'Nomor HP PIC',
                    'required' => true,
                    'autofocus' => true,
                    'label' => 'Nomor HP PIC',
                    'value' => $registeredPerson->pic_phone ?? ''
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

            {{-- Pilih Kartu Visitor (opsional) --}}
            <div class="mb-5">
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                        Pilih Kartu Visitor <span class="text-xs text-gray-500">(opsional)</span>
                    </label>
                    <span id="cardSelectedCount" class="text-xs text-gray-500">0 dipilih</span>
                </div>

                @if($availableCards->count() > 0)
                    <input
                        type="text"
                        id="cardSearchInput"
                        placeholder="Cari nomor kartu..."
                        class="form-control h-[44px] ps-4 mb-2 border-neutral-300 bg-neutral-50 dark:bg-dark-2 rounded-xl w-full"
                    >
                @endif

                <div class="space-y-2 border rounded-lg p-4 max-h-72 overflow-y-auto bg-neutral-50 dark:bg-dark-2">
                    @forelse ($availableCards as $card)
                        <label class="card-option flex items-center gap-3 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-neutral-700" data-card-number="{{ strtolower($card->card_number) }}">
                            <input
                                type="checkbox"
                                name="card_ids[]"
                                value="{{ $card->id }}"
                                id="card-{{ $card->id }}"
                                {{ in_array($card->id, old('card_ids', [])) ? 'checked' : '' }}
                                class="card-checkbox w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                            >
                            <span class="text-sm font-medium text-gray-800 dark:text-gray-100">
                                {{ $card->card_number }}
                            </span>
                        </label>
                    @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada kartu yang tersedia saat ini.</p>
                    @endforelse
                    <p id="cardNoMatch" class="hidden text-sm text-gray-500 dark:text-gray-400">Kartu tidak ditemukan.</p>
                </div>
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

<script>
    // 🔍 Search/filter kartu visitor, supaya tetap enak dipakai walau ada ratusan kartu
    document.addEventListener('DOMContentLoaded', function () {
        const cardSearchInput = document.getElementById('cardSearchInput');
        const cardOptions = document.querySelectorAll('.card-option');
        const cardCheckboxes = document.querySelectorAll('.card-checkbox');
        const cardSelectedCount = document.getElementById('cardSelectedCount');
        const cardNoMatch = document.getElementById('cardNoMatch');

        function updateSelectedCount() {
            if (!cardSelectedCount) return;
            const checked = document.querySelectorAll('.card-checkbox:checked').length;
            cardSelectedCount.textContent = checked + ' dipilih';
        }

        cardCheckboxes.forEach(cb => cb.addEventListener('change', updateSelectedCount));
        updateSelectedCount();

        if (cardSearchInput) {
            cardSearchInput.addEventListener('input', function () {
                const term = this.value.trim().toLowerCase();
                let visibleCount = 0;

                cardOptions.forEach(option => {
                    const match = option.dataset.cardNumber.includes(term);
                    option.style.display = match ? '' : 'none';
                    if (match) visibleCount++;
                });

                if (cardNoMatch) {
                    cardNoMatch.classList.toggle('hidden', visibleCount !== 0 || cardOptions.length === 0);
                }
            });
        }
    });
</script>

@endsection
