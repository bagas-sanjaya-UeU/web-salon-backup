@extends('dashboards.templates.base')

@section('title', 'Dashboard | Detail Booking')


@section('content')
    <div class="container mt-4">
        <h3 class="mb-4">Detail Booking #{{ $booking->id }}</h3>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Informasi Pemesan</h5>
                <p><strong>Nama:</strong>
                    <td>{{ $booking->user->role == 'admin' ? $booking->name : $booking->user->name }}
                </p>
                <p><strong>Email:</strong> {{ $booking->user->email }}</p>
                <p><strong>Telepon:</strong>
                    <td>{{ $booking->user->role == 'admin' ? $booking->phone : $booking->user->phone }}
                </p>
                <p><strong>Alamat:</strong>
                    <td>{{ $booking->user->role == 'admin' ? $booking->address : $booking->user->address }}
                </p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Detail Booking</h5>
                <p><strong>Tanggal Booking:</strong>
                    {{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('l, d F Y') }}</p>
                <p><strong>Waktu:</strong> {{ $booking->booking_time }}</p>
                <p><strong>Layanan:</strong> {{ $booking->services->pluck('service_name')->implode(', ') }}</p>
                <p><strong>Jenis:</strong> {{ $booking->home_service ? 'Home Service' : 'Datang ke Salon' }}</p>
                @if ($booking->home_service)
                    <p><strong>Jarak:</strong> {{ $booking->distance }} KM</p>
                    <p><strong>Ongkir:</strong> Rp{{ number_format($booking->shipping_fee) }}</p>
                @endif
                <p><strong>Total:</strong> Rp{{ number_format($booking->total_price) }}</p>
                <p><strong>Status Booking:</strong> {{ ucfirst($booking->status) }}</p>
                <p><strong>Status Pembayaran:</strong>
                    <span class="{{ $booking->payment_status == 'paid' ? 'text-success' : 'text-danger' }}">
                        {{ ucfirst($booking->payment_status) }}
                    </span>
                </p>
            </div>
        </div>


        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Layanan yang Dipesan</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Layanan</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                            @if ($booking->home_service)
                                <th>Ongkir</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($booking->services as $service)
                            <tr>
                                <td>{{ $service->service_name }}</td>
                                <td>Rp{{ number_format($service->price) }}</td>
                                <td>{{ $service->pivot->quantity ?? 1 }}</td>
                                <td>
                                    Rp{{ number_format($service->price * ($service->pivot->quantity ?? 1)) }}
                                </td>
                                @if ($booking->home_service)
                                    <td>Rp{{ number_format($booking->shipping_fee) }}</td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Dikerjakan oleh</h5>
                <p>
                    @if ($worker)
                        {{ $worker->worker->user->name }}
                    @else
                        <span class="text-danger">Belum ada penugasan</span>
                    @endif
                </p>

                <p><strong>Status:</strong>
                    @if ($worker)
                        @if ($worker->status == 'ongoing')
                            <span class="text-info">Sedang Dikerjakan</span>
                        @elseif($worker->status == 'completed')
                            <span class="text-success">Selesai</span>
                        @else
                            <span class="text-danger">Belum ada penugasan</span>
                        @endif
                    @endif
                </p>
            </div>
        </div>

        <!-- Form dropdown ubah status pembayaran -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Ubah Status Pembayaran</h5>
                <form action="{{ route('dashboard.booking-menu.updatePaymentStatus', $booking->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="form-group">
                        <label for="payment_status">Status Pembayaran</label>
                        <select name="status" id="payment_status" class="form-control">
                            <option value="unpaid" {{ $booking->payment_status == 'unpaid' ? 'selected' : '' }}>Belum Dibayar
                            </option>
                            <option value="paid" {{ $booking->payment_status == 'paid' ? 'selected' : '' }}>Sudah Dibayar
                            </option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Ubah Status Pembayaran</button>
                </form>
            </div>
        </div>

        <div class="mt-4">
            <a href="{{ route('dashboard.bookings.index') }}" class="btn btn-primary">Kembali</a>
        </div>
    </div>
@endsection
