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
    <div>
        <a style="background-color: #007BFF;" class="px-4 py-2 rounded-md text-white text-bold" href="{{ route('registered.create-visitor') }}">Tambah Visitor</a>
    </div>
   </div>

   <div class="bg-white dark:bg-neutral-800 shadow rounded-xl p-4">
      <div class="overflow-x-auto">
         <table id="selection-table" class="w-full border-collapse">
            <thead>
               <tr class="bg-neutral-100 dark:bg-neutral-700 text-left">
                  <th class="px-4 py-3 text-sm font-semibold text-neutral-700 dark:text-neutral-200">No</th>
                  <th class="px-4 py-3 text-sm font-semibold text-neutral-700 dark:text-neutral-200">NID</th>
                  <th class="px-4 py-3 text-sm font-semibold text-neutral-700 dark:text-neutral-200">Name</th>
                  <th class="px-4 py-3 text-sm font-semibold text-neutral-700 dark:text-neutral-200">Company</th>
                  <th class="px-4 py-3 text-sm font-semibold text-neutral-700 dark:text-neutral-200">Status</th>
                  <th class="px-4 py-3 text-sm font-semibold text-neutral-700 dark:text-neutral-200">Action</th>
               </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                @foreach ($visitors as $index => $visitor)
                    <tr>
                        {{-- No --}}
                        <td class="px-4 py-3 text-sm">
                            {{ $index + 1 }}
                        </td>

                        {{-- NID --}}
                        <td class="px-4 py-3 text-sm">
                            {{ $visitor->user->nid ?? '' }}
                        </td>

                        {{-- Name --}}
                        <td class="px-4 py-3 text-sm">
                            {{ $visitor->user->name ?? '' }}
                        </td>

                        {{-- Company --}}
                        <td class="px-4 py-3 text-sm">
                            @if ($isEmployee == 0)
                                {{ $visitor->user->company ?? '-' }}
                            @else
                                -
                            @endif
                        </td>

                        {{-- Status --}}
                        <td class="px-4 py-3 text-sm">
                            @if ($visitor->status_level == 1)
                                <span class="bg-warning-100 dark:bg-warning-600/25 text-warning-600 dark:text-warning-400 px-6 py-1.5 rounded-full font-medium text-sm">
                                    {{ $visitor->status }}
                                </span>
                            @elseif ($visitor->status_level == 0)
                                <span class="bg-danger-100 dark:bg-danger-600/25 text-danger-600 dark:text-danger-400 px-6 py-1.5 rounded-full font-medium text-sm">
                                    {{ $visitor->status }}
                                </span>
                            @else
                                <span class="bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 px-6 py-1.5 rounded-full font-medium text-sm">
                                    {{ $visitor->status }}
                                </span>
                            @endif
                        </td>

                        {{-- Action --}}
                        <td class="px-4 py-3 text-sm">
                            @if ($visitor->status_level == 1)
                                @php
                                    $approveUrl = $isEmployee
                                        ? route('registered.approve', $visitor->id)
                                        : route('registered.approve.visitor', $visitor->id);
                                @endphp

                                <a href="{{ $approveUrl }}"
                                class="w-8 h-8 bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 rounded-full inline-flex items-center justify-center">
                                    <iconify-icon icon="solar:check-circle-outline"></iconify-icon>
                                </a>

                            @elseif ($visitor->status_level == 2 && $isEmployee)
                                <a href="{{ route('registered.edit', $visitor->id) }}"
                                class="w-8 h-8 bg-warning-100 dark:bg-warning-600/25 text-warning-600 dark:text-warning-400 rounded-full inline-flex items-center justify-center">
                                    <iconify-icon icon="solar:pen-2-outline"></iconify-icon>
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>

         </table>
      </div>
   </div>
</div>
@endsection

@section('scripts')
<script>
// $(function () {
//     let table = $('#selection-table').DataTable({
//         processing: true,
//         serverSide: true,
//         ajax: "{{ route('registered.data') }}?is_employee=0",
//         autoWidth: false,
//         responsive: true,
//         columns: [
//             { data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false },
//             { data: 'nid', name: 'user.nid' },
//             { data: 'name', name: 'user.name' },
//             { data: 'company', name: 'company' },
//             { data: 'status', name: 'status', searchable: false },
//             { data: 'action', name: 'action', searchable: false }
//         ],
//         language: {
//             search: "Search ",
//             lengthMenu: "Show _MENU_",
//             info: "Showing _START_ to _END_ of _TOTAL_ entries",
//             paginate: {
//                 previous: "←",
//                 next: "→"
//             }
//         },
//         pagingType: "simple"
//     });

//     // trigger adjust kalau ada resize atau navbar expand/collapse
//     $(window).on('resize', function () {
//         table.columns.adjust().responsive.recalc();
//     });

//     // kalau kamu punya tombol untuk toggle navbar, panggil ini setelah expand/collapse
//     $(document).on('click', '#toggle-navbar', function () {
//         setTimeout(() => {
//             table.columns.adjust().responsive.recalc();
//         }, 300); // kasih delay dikit biar animasi navbar selesai
//     });
// });

</script>
@endsection
