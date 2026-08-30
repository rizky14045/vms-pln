@extends('layout.app')

@section('content')
<div class="p-6">
    <div class="w-full flex items-center justify-between mb-4">
        <h2 class="text-xl font-semibold">Daftar Kartu Visitor</h2>
        <a href="{{ route('visitor-cards.histories') }}"
           style="background-color: #6c757d;" class="px-4 py-2 rounded-md text-white text-bold">
            Riwayat Kartu
        </a>
    </div>

    <div class="bg-white dark:bg-neutral-800 shadow rounded-xl p-4">
        <div class="overflow-x-auto">
            <div class="overflow-x-auto rounded-lg border">
                <form method="GET" action="{{ route('visitor-cards.index') }}"
                    class="mb-4 flex items-center justify-between p-4">

                    <div class="flex flex-wrap items-end gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Search Nomor Kartu</label>
                            <input type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Cari nomor kartu..."
                                class="px-3 py-2 border rounded w-56">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Status</label>
                            <select name="status"
                                    onchange="this.form.submit()"
                                    class="px-3 py-2 border rounded w-44">
                                <option value="" @selected(request('status', '') === '')>Semua Status</option>
                                <option value="1" @selected(request('status') == '1')>Aktif / Tersedia</option>
                                <option value="0" @selected(request('status') == '0')>Tidak Aktif / Dipakai</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit"
                                style="background-color: #007BFF;" class="px-4 py-2 rounded-md text-white text-bold">
                            Filter
                        </button>
                        <a href="{{ route('visitor-cards.create') }}"
                           style="background-color: #14A2BA;" class="px-4 py-2 rounded-md text-white text-bold">
                            Tambah Kartu
                        </a>
                    </div>
                </form>

                <table class="w-full border-collapse text-sm">
                    <thead>
                        <tr class="bg-neutral-100 dark:bg-neutral-700">
                            <th class="px-4 py-3">No</th>
                            <th class="px-4 py-3">Nomor Kartu</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse ($cards as $index => $card)
                            <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-800 transition">
                                <td class="px-4 py-3 text-center">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 font-medium">{{ $card->card_number }}</td>
                                <td class="px-4 py-3 text-center">
                                    @if ($card->status == 1)
                                        <span style="background-color: rgb(20, 216, 53)" class="px-2 py-1 text-white rounded-full text-xs">Aktif / Tersedia</span>
                                    @else
                                        <span style="background-color: gray" class="px-2 py-1 text-white rounded-full text-xs">Tidak Aktif / Dipakai</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('visitor-cards.edit', $card->id) }}" class="w-8 h-8 bg-warning-100 dark:bg-warning-600/25 text-warning-600 dark:text-warning-400 rounded-full inline-flex items-center justify-center">
                                        <iconify-icon icon="solar:pen-2-outline"></iconify-icon>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-neutral-500">
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
@endsection
