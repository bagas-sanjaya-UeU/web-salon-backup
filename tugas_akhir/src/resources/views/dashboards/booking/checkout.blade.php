@extends('dashboards.templates.base')

@section('title', 'Dashboard Booking')

@section('content')
    <div class="container mt-5">

        <h2 class="mb-4 mt-5" style="margin-top: 10px !important;">Checkout Booking</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <h5>Layanan dalam Keranjang:</h5>
        <ul class="list-group mb-4">
            @foreach ($cartItems as $item)
                <li class="list-group-item d-flex justify-content-between">
                    {{ $item->service->service_name }}
                    <span>Rp{{ number_format($item->service->price) }}</span>
                </li>
            @endforeach
        </ul>

        <form action="{{ route('dashboard.checkout.process') }}" method="POST">
            @csrf

            <div class="mb-3 mt-3">
                <label for="name" class="form-label">Nama Pelanggan</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>

            <div class="mb-3 mt-3">
                <label for="address" class="form-label">Alamat Tempat Tinggal</label>
                <input type="text" name="address" id="address" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Nomor Telepon</label>
                <input type="number" min="0" name="phone" id="phone" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="booking_date" class="form-label">Tanggal Booking</label>
                <input type="date" name="booking_date" id="booking_date" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="booking_time" class="form-label">Waktu Booking</label>
                <select name="booking_time" id="booking_time" class="form-select" required>
                    {{-- akan diisi lewat JS --}}
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Jenis Layanan</label>
                <div>
                    <input type="radio" name="home_service" value="0" checked> Datang ke Salon
                    <input type="radio" name="home_service" value="1"> Home Service
                </div>
            </div>

            <!-- Container field dinamis -->
            <div id="dynamic-fields"></div>

            <button type="submit" class="btn btn-primary mt-4 mb-5" style="margin-bottom: 50px;">Konfirmasi
                Booking</button>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        const timeSelect = document.getElementById('booking_time');
        const dateInput = document.getElementById('booking_date');
        const now = new Date();
        const todayDate = now.toISOString().split('T')[0];

        // 🔧 Ubah jam kerja di sini (misal 08:00 - 20:00)
        const startHour = 8; // 08:00
        const endHour = 20; // 20:00

        const allHours = [];
        for (let i = startHour; i <= endHour; i++) {
            allHours.push(i.toString().padStart(2, '0') + ':00');
        }

        dateInput.addEventListener('change', function() {
            const selectedDate = this.value;

            fetch(`/booking/unavailable-times?date=${selectedDate}`)
                .then(res => res.json())
                .then(unavailable => {
                    timeSelect.innerHTML = ''; // kosongkan dulu

                    allHours.forEach(hour => {
                        const isToday = selectedDate === todayDate;
                        const hourNum = parseInt(hour.split(':')[0]);
                        const currentHour = now.getHours();

                        const isPastHour = isToday && hourNum <= currentHour;
                        const isDisabled = unavailable.includes(hour) || isPastHour;

                        const option = document.createElement('option');
                        option.value = hour;
                        option.textContent = hour;

                        if (isDisabled) {
                            option.disabled = true;
                            option.textContent += isPastHour ? ' (Sudah Lewat)' : ' (Sudah Dibooking)';
                        }

                        timeSelect.appendChild(option);
                    });
                });
        });

        // Set tanggal minimum hari ini
        const today = new Date().toISOString().split('T')[0];
        dateInput.setAttribute('min', today);
    </script>


    <script>
        const radios = document.querySelectorAll('input[name="home_service"]');
        const container = document.getElementById('dynamic-fields');

        radios.forEach(r => {
            r.addEventListener('change', function() {
                if (this.value === '1') {
                    // Tambahkan field dan map hanya jika belum ada

                    container.innerHTML = `
                        <div class="mb-3">
                            <label for="distance" class="form-label">Jarak dari Salon (KM)</label>
                            <input type="number" min="0" name="distance" id="distance" class="form-control" >
                        </div>

                        <div class="mb-3">
                            <label for="shipping_fee" class="form-label">Ongkir</label>
                            <input type="number" min="0" name="shipping_fee" id="shipping_fee" class="form-control" >
                        </div>
                    `;

                } else {
                    container.innerHTML = '';
                }
            });
        });
    </script>
@endpush
