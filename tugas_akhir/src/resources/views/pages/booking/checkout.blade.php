@extends('pages.templates.base')

@section('content')
    <div class="container mt-5">

        <h2 class="mb-4 mt-5" style="margin-top: 70px !important;">Checkout Booking</h2>

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

        <form action="{{ route('booking.process') }}" method="POST">
            @csrf
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

@section('script')
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
        // Inisialisasi variabel untuk map dan harga ongkir
        // Ganti dengan koordinat toko Anda
        // Anda bisa menggunakan Google Maps atau OpenStreetMap untuk mendapatkan koordinat ini
        // Klik kanan pada lokasi di peta dan pilih "What's here?" untuk mendapatkan koordinat
        // Misal: -6.26222430306444, 106.52494354240288 (Jakarta)

        // Ganti dengan koordinat toko Anda
        
        const tokoLat = -6.259472586574895; // koordinat toko
        const tokoLng = 106.46676207733233; // koordinat toko
        let hargaPerKm = 2000; // harga per km
        let leafletMap; // buat map global agar gak di-inisialisasi dua kali

        const radios = document.querySelectorAll('input[name="home_service"]');
        const container = document.getElementById('dynamic-fields');

        radios.forEach(r => {
            r.addEventListener('change', function() {
                if (this.value === '1') {
                    // Tambahkan field dan map hanya jika belum ada
                    if (!document.getElementById('address')) {
                        container.innerHTML = `
                        <div class="mb-3 mt-3">
                            <label for="address" class="form-label">Alamat Tempat Tinggal</label>
                            <input type="text" name="address" id="address" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Nomor Telepon</label>
                            <input type="number" min="0" name="phone" id="phone" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="distance" class="form-label">Jarak dari Salon (KM)</label>
                            <input type="number" name="distance" id="distance" class="form-control" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="shipping_fee" class="form-label">Ongkir</label>
                            <input type="number" name="shipping_fee" id="shipping_fee" class="form-control" readonly>
                        </div>

                        <div id="map" style="height: 400px; width: 100%;" class="mt-3"></div>
                    `;

                        // Inisialisasi Map setelah #map ada
                        setTimeout(() => {
                            initMap();
                        }, 100); // kasih delay sedikit biar DOM ready
                    }
                } else {
                    container.innerHTML = '';
                    leafletMap = null; // reset map
                }
            });
        });

        function initMap() {
            leafletMap = L.map('map').setView([tokoLat, tokoLng], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(leafletMap);

            L.marker([tokoLat, tokoLng]).addTo(leafletMap).bindPopup("📍 Toko").openPopup();

            navigator.geolocation.getCurrentPosition(async function(pos) {
                    const userLat = pos.coords.latitude;
                    const userLng = pos.coords.longitude;

                    L.marker([userLat, userLng]).addTo(leafletMap).bindPopup("🧍 Kamu").openPopup();

                    const response = await fetch(
                        'https://api.openrouteservice.org/v2/directions/driving-car/geojson', {
                            method: 'POST',
                            headers: {
                                'Authorization': '5b3ce3597851110001cf6248429941b5db0145d58863c42157fe9856',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                coordinates: [
                                    [userLng, userLat],
                                    [tokoLng, tokoLat]
                                ]
                            })
                        });

                    const data = await response.json();
                    const coords = data.features[0].geometry.coordinates.map(coord => [coord[1], coord[0]]);
                    const routeLine = L.polyline(coords, {
                        color: 'blue'
                    }).addTo(leafletMap);
                    leafletMap.fitBounds(routeLine.getBounds());

                    const distanceInKm = data.features[0].properties.summary.distance / 1000;
                    const ongkir = Math.ceil(distanceInKm * hargaPerKm);

                    const distanceInput = document.getElementById('distance');
                    const shippingFeeInput = document.getElementById('shipping_fee');

                    if (distanceInput && shippingFeeInput) {
                        distanceInput.value = distanceInKm.toFixed(2);
                        shippingFeeInput.value = ongkir;
                    }
                },
                function() {
                    alert("Gagal mendapatkan lokasi pengguna.");
                });
        }
    </script>
@endsection
