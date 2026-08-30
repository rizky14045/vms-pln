@extends('layout.app')
@section('styles')
    <style>
       .dataTables_length select {
            @apply border border-gray-300 rounded-md px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500;
            appearance: none;
            -webkit-appearance: none; /* Safari & Chrome */
            -moz-appearance: none;    /* Firefox */
            background-image: none !important; /* pastikan gak ada icon */
        }


    </style>
@endsection
@section('content')
<div class="p-6">
   <div class="w-full flex items-center justify-between mb-4">
    <h2 class="text-xl font-semibold mb-4">Daftar Request Visitor</h2>
    <div class="flex gap-2">
        <button type="button" id="btnOpenReturnCard" style="background-color: #6c757d;" class="px-4 py-2 rounded-md text-white text-bold">
            Kembalikan Kartu
        </button>
        <a style="background-color: #007BFF;" class="px-4 py-2 rounded-md text-white text-bold" href="{{ route('registered.create-visitor') }}">Tambah Visitor</a>
    </div>
   </div>

   {{-- Modal Kembalikan Kartu --}}
   <div id="returnCardModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div style="max-width: 40%" class="bg-white dark:bg-neutral-800 rounded-xl shadow-lg w-full max-h-[40vh] flex flex-col">
            <div class="px-6 pt-6">
                <h3 class="text-lg font-semibold mb-4">Kembalikan Kartu Visitor</h3>
            </div>

            <form method="POST" action="{{ route('visitor-cards.return') }}" class="flex flex-col flex-1 min-h-0 px-6 pb-6">
                @csrf

                <div class="flex items-center justify-between mb-2 flex-shrink-0">
                    <span class="text-xs text-gray-500">Pilih kartu yang mau dikembalikan</span>
                    <span id="returnCardSelectedCount" class="text-xs text-gray-500">0 dipilih</span>
                </div>

                @if($inUseCards->count() > 0)
                    <input
                        type="text"
                        id="returnCardSearchInput"
                        placeholder="Cari nomor kartu..."
                        class="form-control h-[44px] ps-4 mb-2 border-neutral-300 bg-neutral-50 dark:bg-dark-2 rounded-xl w-full flex-shrink-0"
                    >
                @endif

                <div class="space-y-2 border rounded-lg p-4 overflow-y-auto bg-neutral-50 dark:bg-dark-2 flex-1 min-h-0">
                    @forelse ($inUseCards as $card)
                        <label class="return-card-option flex items-center gap-3 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-neutral-700" data-card-number="{{ strtolower($card->card_number) }}">
                            <input type="checkbox" name="card_ids[]" value="{{ $card->id }}"
                                class="return-card-checkbox w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm font-medium text-gray-800 dark:text-gray-100">{{ $card->card_number }}</span>
                        </label>
                    @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada kartu yang sedang dipakai.</p>
                    @endforelse
                    <p id="returnCardNoMatch" class="hidden text-sm text-gray-500 dark:text-gray-400">Kartu tidak ditemukan.</p>
                </div>

                <div class="flex justify-end gap-2 mt-4 flex-shrink-0">
                    <button type="button" id="btnCloseReturnCard" class="px-4 py-2 rounded-md bg-gray-300 text-gray-800">
                        Batal
                    </button>
                    <button style="background-color: #007BFF;" type="submit" class="px-4 py-2 rounded-md bg-green-600 text-white">
                        Kembalikan
                    </button>
                </div>
            </form>
        </div>
   </div>

   <div class="bg-white dark:bg-neutral-800 shadow rounded-xl p-4">
      <div class="overflow-x-auto">
         <div class="overflow-x-auto rounded-lg border">
            <form method="GET" action="{{ route('registered.index.visitor') }}"
                class="mb-4 flex items-center justify-between p-4">

                <div class="flex flex-wrap items-end gap-4">
                    {{-- Search --}}
                    <div>
                        <label class="block text-sm font-medium mb-1">Search Name</label>
                        <input type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari nama visitor..."
                            class="px-3 py-2 border rounded w-56">
                    </div>

                    {{-- Order By --}}
                    <div>
                        <label class="block text-sm font-medium mb-1">Order By</label>
                        <select name="order_by"
                                onchange="this.form.submit()"
                                class="px-3 py-2 border rounded w-40">
                            <option value="created_at" @selected(request('order_by') == 'created_at')>
                                Created At
                            </option>
                            <option value="name" @selected(request('order_by') == 'name')>
                                Name
                            </option>
                            <option value="status_level" @selected(request('order_by') == 'status_level')>
                                Status
                            </option>
                        </select>
                    </div>

                    {{-- Order Direction --}}
                    <div>
                        <select name="order_dir"
                                onchange="this.form.submit()"
                                class="px-3 py-2 border rounded w-32">
                            <option value="desc" @selected(request('order_dir', 'desc') == 'desc')>
                                Z-A
                            </option>
                            <option value="asc" @selected(request('order_dir') == 'asc')>
                                A-Z
                            </option>
                        </select>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-sm font-medium mb-1">Status</label>
                        <select name="status_level"
                                onchange="this.form.submit()"
                                class="px-3 py-2 border rounded w-44">
                            <option value="" @selected(request('status_level', '') === '')>Semua Status</option>
                            <option value="1" @selected(request('status_level') == '1')>Waiting for approval</option>
                            <option value="2" @selected(request('status_level') == '2')>Approved</option>
                            <option value="0" @selected(request('status_level') == '0')>Rejected</option>
                            <option value="3" @selected(request('status_level') == '3')>Expired</option>
                            <option value="4" @selected(request('status_level') == '4')>Deleted</option>
                        </select>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="flex items-end gap-2">
                    <button type="submit"
                            style="background-color: #007BFF;" class="px-4 py-2 rounded-md text-white text-bold">
                        Filter
                    </button>
                    <a href="{{ route('registered.export.visitor', request()->query()) }}"
                       style="background-color: #198754;" class="px-4 py-2 rounded-md text-white text-bold">
                        Export Excel
                    </a>
                </div>
            </form>

            <table class="w-full border-collapse text-sm">
                <thead>
                    <tr class="bg-neutral-100 dark:bg-neutral-700">
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Company</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-center">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse ($visitors as $index => $visitor)
                        <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-800 transition">
                            <td class="px-4 py-3 text-center">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 text-center">{{ $visitor->user->email ?? '-' }}</td>
                            <td class="px-4 py-3 font-medium text-center">{{ $visitor->user->name ?? '-' }}</td>

                            <td class="px-4 py-3 text-center">
                                {{ $visitor->user->is_employee ? '-' : ($visitor->user->company ?? '-') }}
                            </td>

                            <td class="px-4 py-3 text-center">
                                @if ($visitor->status_level == 1)
                                    <span style="background-color: yellow" class="px-2 py-1 bg-yellow-100 bg-yellow-800 text-black rounded-full text-xs">{{ $visitor->status }}</span>
                                @elseif ($visitor->status_level == 0)
                                    <span style="background-color: red" class="px-2 py-1 bg-red-100 bg-red-800 text-white rounded-full text-xs">{{ $visitor->status }}</span>
                                @elseif ($visitor->status_level == 3)
                                    <span style="background-color: gray" class="px-2 py-1 bg-gray-100 bg-gray-800 text-white rounded-full text-xs">{{ $visitor->status }}</span>
                                @elseif ($visitor->status_level == 5)
                                    <span style="background-color: #6c757d" class="px-2 py-1 text-white rounded-full text-xs">{{ $visitor->status }}</span>
                                @else
                                    <span style="background-color: rgb(20, 216, 53)" class="px-2 py-1 bg-green-100 bg-green-800 text-white rounded-full text-xs">{{ $visitor->status }}</span>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-center">
                                <div class="flex gap-2 items-center justify-center">
                                    @if ($visitor->status_level == 1)
                                        <a href="{{ $visitor->user->is_employee
                                            ? route('registered.approve', $visitor->id)
                                            : route('registered.approve.visitor', $visitor->id) }}"
                                        class="action-btn success">
                                            <iconify-icon icon="solar:check-circle-outline"></iconify-icon>
                                        </a>
                                    @elseif($visitor->status_level > 1 && $visitor->status_level != 5)
                                    <a href="{{ $visitor->user->is_employee
                                            ? route('registered.approve', $visitor->id)
                                            : route('registered.edit-visitor', $visitor->id) }}"
                                        class="action-btn success">
                                            <iconify-icon icon="iconoir:arrow-right" class="icon active"></iconify-icon>
                                        </a>
                                    @endif

                                    @if (!$visitor->user->is_employee && in_array($visitor->status_level, [0, 3, 4, 5]))
                                        <a href="{{ route('registered.approve.visitor', $visitor->id) }}"
                                            title="Aktifkan Kembali"
                                            class="w-8 h-8 bg-warning-100 dark:bg-warning-600/25 text-warning-600 dark:text-warning-400 rounded-full inline-flex items-center justify-center">
                                            <iconify-icon icon="solar:pen-2-outline"></iconify-icon>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-neutral-500">
                                Data tidak ditemukan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

      </div>
   </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('returnCardModal');
    const btnOpen = document.getElementById('btnOpenReturnCard');
    const btnClose = document.getElementById('btnCloseReturnCard');

    btnOpen.addEventListener('click', () => modal.classList.remove('hidden'));
    btnClose.addEventListener('click', () => modal.classList.add('hidden'));
    modal.addEventListener('click', (e) => {
        if (e.target === modal) modal.classList.add('hidden');
    });

    // 🔍 Search/filter kartu, supaya tetap enak dipakai walau ada ratusan kartu
    const returnCardSearchInput = document.getElementById('returnCardSearchInput');
    const returnCardOptions = document.querySelectorAll('.return-card-option');
    const returnCardCheckboxes = document.querySelectorAll('.return-card-checkbox');
    const returnCardSelectedCount = document.getElementById('returnCardSelectedCount');
    const returnCardNoMatch = document.getElementById('returnCardNoMatch');

    function updateReturnCardSelectedCount() {
        if (!returnCardSelectedCount) return;
        const checked = document.querySelectorAll('.return-card-checkbox:checked').length;
        returnCardSelectedCount.textContent = checked + ' dipilih';
    }

    returnCardCheckboxes.forEach(cb => cb.addEventListener('change', updateReturnCardSelectedCount));
    updateReturnCardSelectedCount();

    if (returnCardSearchInput) {
        returnCardSearchInput.addEventListener('input', function () {
            const term = this.value.trim().toLowerCase();
            let visibleCount = 0;

            returnCardOptions.forEach(option => {
                const match = option.dataset.cardNumber.includes(term);
                option.style.display = match ? '' : 'none';
                if (match) visibleCount++;
            });

            if (returnCardNoMatch) {
                returnCardNoMatch.classList.toggle('hidden', visibleCount !== 0 || returnCardOptions.length === 0);
            }
        });
    }
});
</script>
@endsection