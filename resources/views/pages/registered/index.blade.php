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
   <h2 class="text-xl font-semibold mb-4">Daftar Register Employee</h2>

   <div class="bg-white dark:bg-neutral-800 shadow rounded-xl p-4">
      <div class="overflow-x-auto">
         <table id="selection-table" class="w-full border-collapse">
            <thead>
               <tr class="bg-neutral-100 dark:bg-neutral-700 text-left">
                  <th class="px-4 py-3 text-sm font-semibold text-neutral-700 dark:text-neutral-200">No</th>
                  <th class="px-4 py-3 text-sm font-semibold text-neutral-700 dark:text-neutral-200">NID</th>
                  <th class="px-4 py-3 text-sm font-semibold text-neutral-700 dark:text-neutral-200">Name</th>
                  <th class="px-4 py-3 text-sm font-semibold text-neutral-700 dark:text-neutral-200">Status</th>
                  <th class="px-4 py-3 text-sm font-semibold text-neutral-700 dark:text-neutral-200">Action</th>
               </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700"></tbody>
         </table>
      </div>
   </div>
</div>
@endsection

@section('scripts')
<script>
$(function () {
    let table = $('#selection-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('registered.data') }}",
        autoWidth: false,
        responsive: true,
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false },
            { data: 'nid', name: 'user.nid' },
            { data: 'name', name: 'user.name' },
            { data: 'status', name: 'status', searchable: false },
            { data: 'action', name: 'action', searchable: false }
        ],
        language: {
            search: "Search ",
            lengthMenu: "Show _MENU_",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            paginate: {
                previous: "←",
                next: "→"
            }
        },
        pagingType: "simple"
    });

    // trigger adjust kalau ada resize atau navbar expand/collapse
    $(window).on('resize', function () {
        table.columns.adjust().responsive.recalc();
    });

    // kalau kamu punya tombol untuk toggle navbar, panggil ini setelah expand/collapse
    $(document).on('click', '#toggle-navbar', function () {
        setTimeout(() => {
            table.columns.adjust().responsive.recalc();
        }, 300); // kasih delay dikit biar animasi navbar selesai
    });
});

</script>
@endsection
