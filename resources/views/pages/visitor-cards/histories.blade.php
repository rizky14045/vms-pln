@extends('layout.app')

@section('content')
<div class="p-6">
    <h2 class="text-xl font-semibold mb-4">Riwayat Penggunaan Kartu Visitor</h2>

    <div class="bg-white dark:bg-neutral-800 shadow rounded-xl p-4">
        <div class="overflow-x-auto">
            <div class="overflow-x-auto rounded-lg border">
                <form method="GET" action="{{ route('visitor-cards.histories') }}"
                    class="mb-4 flex items-center justify-between p-4 flex-wrap gap-4">

                    <div class="flex flex-wrap items-end gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Dari Tanggal</label>
                            <input type="date" name="start_date" value="{{ request('start_date') }}"
                                class="px-3 py-2 border rounded w-44">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Sampai Tanggal</label>
                            <input type="date" name="end_date" value="{{ request('end_date') }}"
                                class="px-3 py-2 border rounded w-44">
                        </div>
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit"
                                style="background-color: #007BFF;" class="px-4 py-2 rounded-md text-white text-bold">
                            Filter
                        </button>
                        <a href="{{ route('visitor-cards.histories.export', request()->query()) }}"
                           style="background-color: #198754;" class="px-4 py-2 rounded-md text-white text-bold">
                            Export Excel
                        </a>
                    </div>
                </form>

                <table class="w-full border-collapse text-sm">
                    <thead>
                        <tr class="bg-neutral-100 dark:bg-neutral-700">
                            <th class="px-4 py-3">No</th>
                            <th class="px-4 py-3">Nama Peminjam</th>
                            <th class="px-4 py-3">Nomor Kartu</th>
                            <th class="px-4 py-3">Mulai</th>
                            <th class="px-4 py-3">Selesai</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse ($histories as $index => $history)
                            <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-800 transition">
                                <td class="px-4 py-3 text-center">{{ $index + 1 }}</td>
                                <td class="px-4 py-3">{{ $history->user->name ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $history->visitorCard->card_number ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    {{ optional($history->borrowed_at)->format('d-m-Y H:i') }}
                                </td>
                                <td class="px-4 py-3">
                                    @if ($history->returned_at)
                                        {{ $history->returned_at->format('d-m-Y H:i') }}
                                    @else
                                        <span style="background-color: yellow" class="px-2 py-1 text-black rounded-full text-xs">Belum Dikembalikan</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-neutral-500">
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
