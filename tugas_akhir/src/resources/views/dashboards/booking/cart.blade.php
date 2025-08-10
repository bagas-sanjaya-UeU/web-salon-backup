@extends('dashboards.templates.base')

@section('title', 'Dashboard Booking')

@section('content')
    <div class="container mt-5" style="min-height: 80vh;">
        <h2 class="mb-4 mt-5" style="margin-top: 70px !important;">Keranjang Layanan</h2>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if ($cartItems->isEmpty())
            <p>Keranjang kamu kosong.</p>
            <a href="{{ route('dashboard.booking-menu.index') }}" class="btn btn-primary mb-3">Kembali ke Layanan</a>
        @else
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Layanan</th>
                        <th>Harga</th>
                        <th>Qty</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cartItems as $item)
                        <tr>
                            <td>{{ $item->service->service_name }}</td>
                            <td>Rp{{ number_format($item->service->price) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>Rp{{ number_format($item->service->price * $item->quantity) }}</td>
                            <td>
                                <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <a href="{{ route('booking.admin.checkout') }}" class="btn btn-primary mb-3">Lanjut Booking</a>
        @endif
    </div>
@endsection
