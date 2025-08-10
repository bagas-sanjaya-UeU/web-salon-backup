@extends('pages.templates.base')

@section('content')
    <div class="container d-flex align-items-center justify-content-center" style="min-height: 85vh;">
        <div class="text-center">
            <div class="mb-4">
                <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
            </div>
            <h2 class="fw-bold">Pembayaran Pending!</h2>
            <p class="mt-2 mb-4 text-muted">
                Pembayaran Anda sedang dalam proses verifikasi.<br>
                Silakan tunggu beberapa saat hingga pembayaran Anda dikonfirmasi oleh tim kami. ⏳
            </p>
            <a href="{{ route('home') }}" class="btn btn-outline-primary btn-lg">
                <i class="bi bi-house-door-fill me-1"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
@endsection
