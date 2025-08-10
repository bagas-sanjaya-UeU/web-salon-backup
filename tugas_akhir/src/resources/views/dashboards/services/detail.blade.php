@extends('dashboards.templates.base')

@section('title', 'Dashboard | Detail Layanan')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4 class="card-title mt-4">Detail Layanan</h4>
                <div class="card mb-3" style="max-width: 540px;">
                    <div class="row g-0">
                        <div class="col-md-4">
                            @if ($service->image)
                                <img src="{{ asset('storage/images/services/' . $service->image) }}"
                                    class="img-fluid rounded-start" alt="{{ $service->name }}">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ $service->name }}"
                                    class="img-fluid rounded-start" alt="{{ $service->name }}">
                            @endif
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">
                                    {{ $service->service_name }}
                                </h5>
                                <p class="card-text">
                                    <strong> Deskripsi: </strong> {{ $service->description }}
                                </p>
                                <p class="card-text">
                                    <strong> Harga: </strong> {{ $service->price }}
                                </p>
                                <p class="card-text">
                                    <strong> Home Service: </strong> {{ $service->home_service ? 'Ya' : 'Tidak' }}
                                </p>

                                <p class="card-text"><small class="text-body-secondary">Last updated 3 mins ago</small></p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- kembali --}}
                <a href="{{ route('dashboard.services.index') }}" class="btn btn-primary">Kembali</a>
            </div>
        </div>
    </div>
@endsection
