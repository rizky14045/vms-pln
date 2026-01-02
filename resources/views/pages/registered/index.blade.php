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
         <div class="overflow-x-auto rounded-lg border">
            <form method="GET" action="{{ route('registered.index') }}"
                class="mb-4 flex items-center justify-between p-4">

                <div class="flex flex-wrap items-end gap-4">
                    {{-- Search --}}
                    <div>
                        <label class="block text-sm font-medium mb-1">Search Name</label>
                        <input type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari nama karyawan..."
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
                </div>

                {{-- Submit --}}
                <div>
                    <button type="submit"
                            style="background-color: #007BFF;" class="px-4 py-2 rounded-md text-white text-bold">
                        Filter
                    </button>
                </div>
            </form>

            <table class="w-full border-collapse text-sm">
                <thead>
                    <tr class="bg-neutral-100 dark:bg-neutral-700">
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">NID</th>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-center">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse ($visitors as $index => $visitor)
                        <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-800 transition">
                            <td class="px-4 py-3 text-center">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 text-center">{{ $visitor->user->nid ?? '-' }}</td>
                            <td class="px-4 py-3 font-medium text-center">{{ $visitor->user->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-center">
                                @if ($visitor->status_level == 1)
                                    <span style="background-color: yellow" class="px-2 py-1 bg-yellow-100 bg-yellow-800 text-black rounded-full text-xs">{{ $visitor->status }}</span>
                                @elseif ($visitor->status_level == 0)
                                    <span style="background-color: red" class="px-2 py-1 bg-red-100 bg-red-800 text-white rounded-full text-xs">{{ $visitor->status }}</span>
                                @elseif ($visitor->status_level == 3)
                                    <span style="background-color: gray" class="px-2 py-1 bg-gray-100 bg-gray-800 text-white rounded-full text-xs">{{ $visitor->status }}</span>
                                @else
                                    <span style="background-color: rgb(20, 216, 53)" class="px-2 py-1 bg-green-100 bg-green-800 text-white rounded-full text-xs">{{ $visitor->status }}</span>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-center">
                                @if ($visitor->status_level == 1)
                                    <a href="{{ $visitor->user->is_employee
                                        ? route('registered.approve', $visitor->id)
                                        : route('registered.approve.visitor', $visitor->id) }}"
                                    class="action-btn success">
                                        <iconify-icon icon="solar:check-circle-outline"></iconify-icon>
                                    </a>
                                @elseif($visitor->status_level == 2)
                                   <div class="flex gap-4 items-center justify-center">
                                        <a href="{{ route('registered.edit', $visitor->id) }}" class="w-8 h-8 bg-warning-100 dark:bg-warning-600/25 text-warning-600 dark:text-warning-400 rounded-full inline-flex items-center justify-center">
                                            <iconify-icon icon="solar:pen-2-outline"></iconify-icon>
                                        </a>
                                        <form method="POST" action="{{ route('registered.delete', $visitor->id) }}" style="display:inline;">
                                            @method('PATCH')
                                            @csrf
                                            <button type="submit" class="w-8 h-8 bg-danger-100 dark:bg-danger-600/25 text-danger-600 dark:text-danger-400 rounded-full inline-flex items-center justify-center" onclick="return confirm('Are you sure you want to delete this data?')">
                                                <iconify-icon icon="solar:trash-bin-trash-outline"></iconify-icon>
                                            </button>
                                        </form>
                                   </div>
                                @endif
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
@endsection