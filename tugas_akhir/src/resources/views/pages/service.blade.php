@extends('pages.templates.base')

@section('content')
    <div class="container my-5">
        <h2 class="text-center section-title ">Layanan Kami</h2>
        <div class="row g-4">
            @forelse ($services as $service)
                <div class="col-md-4">
                    <div class="card card-custom">
                        @if ($service->image)
                            <img src="{{ asset('storage/images/services/' . $service->image) }}" class="card-img-top"
                                alt="{{ $service->title }}">
                        @else
                            <img src="{{ asset('images/default-service.jpg') }}" class="card-img-top" alt="Default Image">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $service->service_name }}</h5>
                            <p class="card-text">{!! Str::limit($service->description, 100, '...') !!}</p>
                            <p class="card-text">Harga: <strong>Rp.
                                    {{ // Format harga sesuai kebutuhan
                                        number_format($service->price, 0, ',', '.') }}</strong>
                            </p>
                            <p class="card-text">
                                <small class="text-muted">{{ $service->is_home_service ? 'Layanan Home Service' : 'Layanan di Tempat' }}</small>
                        </div>
                        <div class="card-footer bg-transparent border-0">
                            <a href="{{ route('services.detail', $service->id) }}" class="btn btn-custom">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p>Tidak ada layanan yang tersedia saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
