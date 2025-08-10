@extends('dashboards.templates.base')

@section('title', 'Dashboard')

@if (Auth::user()->role == 'admin')
    @section('chart')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // ... (Kode JavaScript untuk grafik tidak perlu diubah, jadi saya singkat)
                function grafikSkorTotalperBulan() {
                    const categories = @json($bulan);
                    const bookingData = @json($bookingCounts);
                    let options = {
                        series: [{ name: 'Jumlah Booking', data: bookingData }],
                        chart: { type: 'bar', height: 350, parentHeightOffset: 0, toolbar: { show: false } },
                        plotOptions: { bar: { horizontal: false, columnWidth: '55%', endingShape: 'rounded' } },
                        dataLabels: { enabled: false },
                        stroke: { show: true, width: 2, colors: ['transparent'] },
                        xaxis: {
                            title: { text: 'Bulan di Tahun {{ now()->year }}' },
                            categories: categories,
                        },
                        yaxis: { title: { text: 'Jumlah Booking' } },
                        fill: { opacity: 1 },
                        tooltip: { y: { formatter: (val) => val + " Booking" } }
                    };
                    let chart = new ApexCharts(document.querySelector("#transaksiBulanan"), options);
                    chart.render();
                }

                function grafikSkorTotalperHari() {
                    const hariLabels = @json($tanggalHarian);
                    const hariData = @json($jumlahHarian);
                    let options = {
                        series: [{ name: 'Jumlah Booking', data: hariData }],
                        chart: { type: 'line', height: 350, parentHeightOffset: 0, toolbar: { show: false } },
                        xaxis: {
                            title: { text: 'Tanggal di Bulan {{ now()->monthName }}' },
                            categories: hariLabels,
                        },
                        yaxis: { title: { text: 'Jumlah Booking' } },
                        tooltip: { y: { formatter: (val) => val + " Booking" } }
                    };
                    let chart = new ApexCharts(document.querySelector("#transaksiHarian"), options);
                    chart.render();
                }

                function grafikSkorTotalperMinggu() {
                    const mingguLabels = @json($minggu);
                    const mingguData = @json($jumlahMingguan);
                    let options = {
                        series: [{ name: 'Jumlah Booking', data: mingguData }],
                        chart: { type: 'bar', height: 350, parentHeightOffset: 0, toolbar: { show: false } },
                        plotOptions: { bar: { horizontal: false, columnWidth: '55%', endingShape: 'rounded' } },
                        dataLabels: { enabled: false },
                        stroke: { show: true, width: 2, colors: ['transparent'] },
                        xaxis: {
                            title: { text: 'Minggu di Tahun {{ now()->year }}' },
                            categories: mingguLabels,
                        },
                        yaxis: { title: { text: 'Jumlah Booking' } },
                        fill: { opacity: 1 },
                        tooltip: { y: { formatter: (val) => val + " Booking" } }
                    };
                    let chart = new ApexCharts(document.querySelector("#transaksiPerminggu"), options);
                    chart.render();
                }

                grafikSkorTotalperBulan();
                grafikSkorTotalperHari();
                grafikSkorTotalperMinggu();
            });
        </script>
    @endsection
@endif


@section('content')
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ session('error') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">

            {{-- =============================================== --}}
            {{-- | Tampilan untuk ROLE ADMIN                   | --}}
            {{-- =============================================== --}}
            @if (Auth::user()->role == 'admin')
                <div class="col-12 mb-4">
                    <div class="row">
                        <div class="col-lg-2 col-md-4 col-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <div class="avatar flex-shrink-0 mx-auto mb-2">
                                        <i class='bx bx-user fs-1'></i>
                                    </div>
                                    <span>Pelanggan</span>
                                    <h3 class="card-title text-nowrap mb-1">{{ $users }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <div class="avatar flex-shrink-0 mx-auto mb-2">
                                        <i class='bx bxs-spreadsheet fs-1'></i>
                                    </div>
                                    <span>Layanan</span>
                                    <h3 class="card-title text-nowrap mb-1">{{ $services }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <div class="avatar flex-shrink-0 mx-auto mb-2">
                                        <i class='bx bx-briefcase-alt fs-1'></i>
                                    </div>
                                    <span>Pekerja</span>
                                    <h3 class="card-title text-nowrap mb-1">{{ $workers }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <div class="avatar flex-shrink-0 mx-auto mb-2">
                                        <i class='bx bx-book-bookmark fs-1'></i>
                                    </div>
                                    <span>Booking</span>
                                    <h3 class="card-title text-nowrap mb-1">{{ $bookings }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <div class="avatar flex-shrink-0 mx-auto mb-2">
                                        <i class='bx bx-group fs-1'></i>
                                    </div>
                                    <span>Pengunjung</span>
                                    <h3 class="card-title text-nowrap mb-1">{{ $visitors }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <div class="avatar flex-shrink-0 mx-auto mb-2">
                                        <i class='bx bx-dollar-circle fs-1'></i>
                                    </div>
                                    <span>Uang Masuk</span>
                                    <h4 class="card-title text-nowrap mb-1 fs-5">
                                        Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 order-2 order-md-3 order-lg-2 mb-4">
                    <div class="card">
                        <h5 class="card-header m-0 me-2 pb-3">Grafik Transaksi Bulanan</h5>
                        <div id="transaksiBulanan" class="px-2"></div>
                    </div>
                </div>

                <div class="col-lg-6 order-2 order-md-3 order-lg-2 mb-4">
                    <div class="card">
                        <h5 class="card-header m-0 me-2 pb-3">Grafik Transaksi Harian (Bulan Ini)</h5>
                        <div id="transaksiHarian" class="px-2"></div>
                    </div>
                </div>

                <div class="col-lg-6 order-2 order-md-3 order-lg-2 mb-4">
                    <div class="card">
                        <h5 class="card-header m-0 me-2 pb-3">Grafik Transaksi Mingguan (Tahun Ini)</h5>
                        <div id="transaksiPerminggu" class="px-2"></div>
                    </div>
                </div>
            @endif

            {{-- =============================================== --}}
            {{-- | Tampilan untuk ROLE USER / WORKER           | --}}
            {{-- =============================================== --}}
            @if (Auth::user()->role == 'user' || Auth::user()->role == 'worker')
                <div class="col-12">
                    <div class="row">
                        @if (Auth::user()->role == 'user')
                            <div class="col-lg-3 col-md-6 col-12 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-title d-flex align-items-start justify-content-between">
                                            <div class="avatar flex-shrink-0">
                                                <i class='bx bx-book-bookmark' style="font-size: 50px;"></i>
                                            </div>
                                        </div>
                                        <span>Total Booking Anda</span>
                                        <h3 class="card-title text-nowrap mb-1">{{ $bookings }}</h3>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (Auth::user()->role == 'worker')
                            <div class="col-lg-3 col-md-6 col-12 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-title d-flex align-items-start justify-content-between">
                                            <div class="avatar flex-shrink-0">
                                                <i class='bx bx-briefcase' style="font-size: 50px;"></i>
                                            </div>
                                        </div>
                                        <span>Total Pekerjaan Selesai</span>
                                        <h3 class="card-title text-nowrap mb-1">{{ $histories }}</h3>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

        </div>
    </div>
@endsection