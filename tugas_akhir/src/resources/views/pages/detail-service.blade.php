@extends('pages.templates.base')

@section('content')
    <div class="container my-5">
        <h2 class="text-center section-title mb-4">Detail Layanan</h2>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card detail-card mb-4">
                    <div class="row g-0">
                        <div class="col-md-5">
                            @if ($service->image)
                                <img src="{{ asset('storage/images/services/' . $service->image) }}"
                                    class="img-fluid detail-img" alt="{{ $service->service_name }}">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ $service->service_name }}"
                                    class="img-fluid detail-img" alt="{{ $service->service_name }}">
                            @endif
                        </div>
                        <div class="col-md-7">
                            <div class="detail-content">
                                <h3 class="service-title">{{ $service->service_name }}</h3>
                                @if (isset($service->price))
                                    <p class="service-price">Rp. {{ number_format($service->price, 0, ',', '.') }}</p>
                                @endif
                                <p class="service-description">{!! $service->description !!}</p>
                                <p class="service-type">
                                    <strong>Jenis Layanan:</strong>
                                    {{ $service->is_home_service ? 'Home Service' : 'Layanan di Tempat' }}
                                </p>
                                
                                @if (Auth::check())
                                    @if ($service->is_in_cart)
                                        <a href="{{ route('cart.index') }}" class="btn btn-add-cart"><i
                                                class="bi bi-bag-check"></i> Lihat Keranjang</a>
                                    @else
                                        <a href="{{ route('cart.add', $service->id) }}" class="btn btn-add-cart"><i
                                                class="bi bi-cart-plus"></i> Tambah ke
                                            Keranjang</a>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-add-cart">Masuk untuk Menambahkan ke
                                        Keranjang</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Anda dapat menambahkan konten tambahan jika diperlukan -->
            </div>
        </div>
    </div>
@endsection
