@extends('dashboards.templates.base')

@section('title', 'Dashboard Booking')

@section('content')
    <div class="container my-5">
        <h2 class="text-center section-title">Layanan Kami</h2>

        {{-- Responsive scroll container untuk mobile --}}
        <div class="row flex-nowrap overflow-auto g-4 pb-3" style="scroll-snap-type: x mandatory;">
            @php
                $userId = Auth::id();
                $cartServiceIds = \App\Models\Cart::where('user_id', $userId)->pluck('service_id')->toArray();
            @endphp

            @forelse ($services as $service)
                <div class="col-md-4 col-10" style="scroll-snap-align: start;">
                    <div class="card card-custom h-100">
                        @if ($service->image)
                            <img src="{{ asset('storage/images/services/' . $service->image) }}" class="card-img-top"
                                alt="{{ $service->title }}">
                        @else
                            <img src="{{ asset('images/default-service.jpg') }}" class="card-img-top" alt="Default Image">
                        @endif

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $service->service_name }}</h5>

                            {{-- Deskripsi collapsible --}}
                            <p class="card-text" id="desc-{{ $service->id }}">
                                {!! Str::limit($service->description, 100, '...') !!}
                            </p>
                            @if (strlen(strip_tags($service->description)) > 100)
                                <a href="javascript:void(0)" class="text-primary small"
                                    onclick="toggleDescription({{ $service->id }}, `{!! addslashes($service->description) !!}`)">Selengkapnya</a>
                            @endif

                            <p class="card-text mt-auto">Harga: <strong>Rp.
                                    {{ number_format($service->price, 0, ',', '.') }}</strong></p>
                        </div>

                        @php
                            $inCart = in_array($service->id, $cartServiceIds);
                        @endphp

                        <div class="card-footer bg-transparent border-0 text-end">
                            <button type="button"
                                class="btn {{ $inCart ? 'btn-outline-danger' : 'btn-outline-primary' }} toggle-cart"
                                data-id="{{ $service->id }}" data-added="{{ $inCart ? 'true' : 'false' }}">
                                <i class="bx {{ $inCart ? 'bxs-cart-alt' : 'bxs-cart-add' }}"
                                    id="cart-icon-{{ $service->id }}"></i>
                            </button>
                        </div>

                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p>Tidak ada layanan yang tersedia saat ini.</p>
                </div>
            @endforelse
        </div>

        {{-- Tombol di kanan untuk lanjut --}}
        <div class="text-center mt-4">
            <a href="{{ route('cart.admin.index') }}" class="btn btn-primary">
                Lanjutkan ke Keranjang
                @php
                    // cek user yang login
                    $userId = Auth::id();
                    // ambil data keranjang berdasarkan user yang login
                    $cartCount = \App\Models\Cart::where('user_id', $userId)->count();

                @endphp
                @if ($cartCount > 0)
                    {{-- Tampilkan badge jika ada item di keranjang --}}
                    <span class="badge bg-danger" id="cart-count-badge">{{ $cartCount }}</span>
                @endif
                >>
            </a>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function toggleDescription(id, fullText) {
            const desc = document.getElementById('desc-' + id);
            if (desc.innerHTML.includes('...')) {
                desc.innerHTML = fullText;
            } else {
                desc.innerHTML = fullText.substring(0, 100) + '...';
            }
        }

        document.addEventListener("DOMContentLoaded", () => {
            const cartButtons = document.querySelectorAll('.toggle-cart');

            cartButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const serviceId = button.getAttribute('data-id');
                    const isAdded = button.getAttribute('data-added') === 'true';
                    const icon = document.getElementById('cart-icon-' + serviceId);

                    const url = isAdded ?
                        `/cart/remove/api/${serviceId}` :
                        `/cart/add/api/${serviceId}`;
                    const method = isAdded ? 'DELETE' : 'POST';

                    fetch(url, {
                            method: method,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Notifikasi SweetAlert
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'success',
                                    title: data.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                });

                                // Toggle UI
                                if (isAdded) {
                                    icon.classList.remove('bxs-cart-alt');
                                    icon.classList.add('bxs-cart-add');
                                    button.classList.remove('btn-outline-danger');
                                    button.classList.add('btn-outline-primary');
                                    button.setAttribute('data-added', 'false');
                                } else {
                                    icon.classList.remove('bxs-cart-add');
                                    icon.classList.add('bxs-cart-alt');
                                    button.classList.remove('btn-outline-primary');
                                    button.classList.add('btn-outline-danger');
                                    button.setAttribute('data-added', 'true');
                                }

                                // 🔄 Update badge
                                updateCartCount();
                            } else {
                                Swal.fire('Oops!', data.message || 'Gagal update keranjang!',
                                    'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('Error', 'Terjadi kesalahan saat mengirim data.',
                                'error');
                        });
                });
            });

            function updateCartCount() {
                fetch('/cart/count', {
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        const badge = document.getElementById('cart-count-badge');
                        if (badge) {
                            badge.innerText = data.count;
                            badge.style.display = data.count > 0 ? 'inline-block' : 'none';
                        }
                    });
            }
        });
    </script>
@endpush
