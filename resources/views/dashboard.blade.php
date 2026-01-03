@extends('layout.app')

@section('content')
    <div class="dashboard-main-body">

        <!-- TITLE -->
        <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
            <h6 class="font-semibold mb-0 dark:text-white">Visitor Management Dashboard</h6>
        </div>

        <p class="mb-5">Tanggal : {{date('d F Y')}}</p>
        <!-- SUMMARY CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 2xl:grid-cols-6 gap-6">
            <!-- Total Visitor -->
            <div
                class="card shadow-none border border-gray-200 rounded-lg h-full bg-gradient-to-r from-cyan-600/10 to-white">
                <div class="card-body p-5">
                    <p class="font-medium mb-1">Total Register</p>
                    <h6 class="text-2xl font-bold">{{ $totalVisitor }}</h6>
                </div>
            </div>

            <!-- Total Karyawan -->
            <div
                class="card shadow-none border border-gray-200 rounded-lg h-full bg-gradient-to-r from-indigo-600/10 to-white">
                <div class="card-body p-5">
                    <p class="font-medium mb-1">Total Karyawan Aktif</p>
                    <h6 class="text-2xl font-bold">{{ $totalKaryawan }}</h6>
                </div>
            </div>

            <!-- External Visitor -->
            <div class="card shadow-none border border-gray-200 rounded-lg bg-gradient-to-r from-sky-600/10 to-white">
                <div class="card-body p-5">
                    <p class="font-medium mb-1">Visitor External</p>
                    <h6 class="text-2xl font-bold text-sky-600">{{ $totalVisitorExternal }}</h6>

                    <!-- Breakdown -->
                    <div class="mt-4 grid grid-cols-3 gap-3 text-sm">
                        <div>
                            <p class="text-gray-500">Waiting For Approval</p>
                            <p class="font-semibold text-yellow-600">{{ $pendingExternal }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Active Visitor</p>
                            <p class="font-semibold text-green-600">{{ $approvedExternal }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Internal Visitor -->
            {{-- <div class="card shadow-none border border-gray-200 rounded-lg bg-gradient-to-r from-purple-600/10 to-white">
                <div class="card-body p-5">
                    <p class="font-medium mb-1">Visitor Internal</p>
                    <h6 class="text-2xl font-bold text-purple-600">{{ $totalVisitorInternal }}</h6>

                    <!-- Breakdown -->
                    <div class="mt-4 grid grid-cols-3 gap-3 text-sm">
                        <div>
                            <p class="text-gray-500">Waiting For Approval</p>
                            <p class="font-semibold text-yellow-600">{{ $pendingInternal }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Approved</p>
                            <p class="font-semibold text-green-600">{{ $approvedInternal }}</p>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>

        <!-- CHARTS -->
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-6">

            <!-- LINE CHART (FULL WIDTH) -->
            <div class="xl:col-span-12">
                <div class="card h-full rounded-lg border-0">
                    <div class="card-body">
                        <h6 class="text-lg font-semibold mb-3">Visitor Statistic</h6>
                        <canvas id="visitorChart" height="120"></canvas>
                    </div>
                </div>
            </div>

            <!-- LEFT COLUMN: POPULAR AREA -->
            <div class="xl:col-span-4 mt-6">
                <div class="card p-6 rounded-lg h-full">
                    <h6 class="font-semibold mb-3">Popular Visit Area</h6>
                    <canvas id="popularArea" height="300"></canvas>
                </div>
            </div>

            <!-- RIGHT COLUMN: LATEST VISITORS -->
            <div class="xl:col-span-8 mt-6">
                <div class="card p-6 rounded-lg">
                    <h6 class="text-lg font-semibold mb-4">Latest Visitors</h6>

                    <div class="overflow-x-auto">
                        <table class="table bordered-table w-full">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Tempat</th>
                                    <th>Tanggal</th>
                                    <th>Jam</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($latestVisitors as $v)
                                    <tr>
                                        <td>{{ $v->card_name }}</td>
                                        <td>{{ $v->door_name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($v->tr_date)->format('d-m-Y') }}</td>
                                        <td>{{ $v->tr_time }}</td>
                                        
                                @endforeach
                            </tbody>

                        </table>
                    </div>

                </div>
            </div>

            @foreach ($latestEmployeeByArea as $areaId => $employees)
                <div class="xl:col-span-6 mt-6">
                    <div class="card p-6 rounded-lg">
                        <h6 class="text-lg font-semibold mb-4">
                            Data Karyawan @if($areaId == 1) VVIP @elseif($areaId == 2) VIP @endif
                        </h6>

                        <div class="overflow-x-auto">
                            <table class="table bordered-table w-full">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($employees as $index => $emp)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $emp->name }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-neutral-500">
                                                Tidak ada data
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            @endforeach


        </div>

    </div>
@endsection


@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Line Chart
        new Chart(document.getElementById('visitorChart'), {
            type: 'line',
            data: {
                labels: @json($months),
                datasets: [{
                    label: "Visitor",
                    data: @json($visitorPerMonth),
                    borderWidth: 3,
                    borderColor: "#0ea5e9",
                    backgroundColor: "rgba(14,165,233,0.2)",
                    tension: 0.4
                }]
            }
        });

        // Popular Area
        new Chart(document.getElementById('popularArea'), {
            type: 'bar',
            data: {
                labels: @json($areaNames),
                datasets: [{
                    label: "Visits",
                    data: @json($areaCounts),
                    backgroundColor: "#6366f1"
                }]
            }
        });
    </script>
@endsection
