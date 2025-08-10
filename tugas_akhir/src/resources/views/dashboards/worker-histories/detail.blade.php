@extends('dashboards.templates.base')

@section('title', 'Detail Pekerjaan')

@section('content')
    <div class="container mt-4">
        <h4>Detail Pekerjaan #{{ $history->id }}</h4>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Informasi Pekerja</h5>
                <p><strong>Nama:</strong> {{ $history->worker->user->name }}</p>
                <p><strong>No HP:</strong> {{ $history->worker->user->phone }}</p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Dipesan oleh</h5>
                <p><strong>Nama:</strong>
                    {{ $history->booking->user->role == 'admin' ? $history->booking->name : $history->booking->user->name }}
                </p>
                <p><strong>Email:</strong> {{ $history->booking->user->email }}</p>
                <p><strong>Telepon:</strong>
                    {{ $history->booking->user->role == 'admin' ? $history->booking->phone : $history->booking->user->phone }}
                </p>
                <p><strong>Alamat:</strong>
                    {{ $history->booking->user->role == 'admin' ? $history->booking->address : $history->booking->user->address }}
                </p>
                <p><strong>Home Service:</strong>
                    {{ $history->booking->home_service ? 'Ya' : 'Tidak' }}</p>
                </p>
                <p><strong>Tanggal Booking:</strong>
                    {{ \Carbon\Carbon::parse($history->booking->booking_date)->translatedFormat('l, d F Y') }}</p>
                <p><strong>Jam:</strong> {{ $history->booking->booking_time }}</p>
                <p><strong>Status Kerja:</strong> {{ ucfirst($history->status) }}</p>
                <p><strong>Konfirmasi:</strong> {{ ucfirst($history->confirmation_status) }}</p>
            </div>
        </div>

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
                            @if ($history->booking->home_service)
                                <th>Ongkir</th>
                            @endif
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($history->booking->services as $service)
                            <tr>
                                <td>{{ $service->service_name }}</td>
                                <td>Rp{{ number_format($service->price) }}</td>
                                <td>{{ $service->pivot->quantity ?? 1 }}</td>
                                <td>Rp{{ number_format($service->price * ($service->pivot->quantity ?? 1)) }}</td>
                                @if ($history->booking->home_service)
                                    <td>Rp{{ number_format($history->booking->shipping_fee) }}</td>
                                @endif
                                <td>Rp{{ number_format($history->booking->total_price) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <a href="{{ route('dashboard.transaction-history.index') }}" class="btn btn-primary mt-3">Kembali</a>
    </div>
@endsection
