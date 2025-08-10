@extends('dashboards.templates.base')

@section('title', 'Detail Booking')

@section('content')
    <div class="container mt-4">
        <h4>Detail Booking #{{ $booking->id }}</h4>

        {{-- Informasi Pekerja --}}
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Informasi Pekerja</h5>
                @if ($worker)
                    <p><strong>Nama:</strong> {{ $worker->worker->user->name }}</p>
                    <p><strong>No HP:</strong> {{ $worker->worker->user->phone }}</p>
                @else
                    <p class="text-danger">Belum ada pekerja yang ditugaskan.</p>
                @endif
            </div>
        </div>

        {{-- Informasi Booking --}}
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Dipesan oleh</h5>
                <p><strong>Nama:</strong> {{ $booking->user->name }}</p>
                <p><strong>Tanggal Booking:</strong>
                    {{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('l, d F Y') }}</p>
                <p><strong>Jam:</strong> {{ $booking->booking_time }}</p>
                @if ($worker)
                    <p><strong>Status Kerja:</strong> {{ ucfirst($worker->status) }}</p>
                    <p><strong>Konfirmasi:</strong> {{ ucfirst($worker->confirmation_status) }}</p>
                @endif
            </div>
        </div>

        {{-- Layanan --}}
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Layanan yang Dikerjakan</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Layanan</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($booking->services as $service)
                            <tr>
                                <td>{{ $service->service_name }}</td>
                                <td>Rp{{ number_format($service->price) }}</td>
                                <td>{{ $service->pivot->quantity ?? 1 }}</td>
                                <td>Rp{{ number_format($service->price * ($service->pivot->quantity ?? 1)) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <a href="{{ route('user.transaction-history') }}" class="btn btn-primary mt-3">Kembali</a>
    </div>
@endsection
