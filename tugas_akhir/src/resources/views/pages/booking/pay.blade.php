@extends('pages.templates.base')

@section('content')
    <div class="container mt-5" style="min-height: 80vh;">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg border-0" style="margin-top: 70px;">
                    <div class="card-header bg-success text-white text-center">
                        <h4 class="mb-0">Konfirmasi Pembayaran {{ $orderId }}</h4>
                    </div>
                    <div class="card-body text-center">
                        <p class="lead mb-4">Silakan klik tombol di bawah untuk menyelesaikan transaksi Anda.</p>

                        <div class="alert alert-info">
                            <strong>Total yang harus dibayar:</strong><br>
                            <span style="font-size: 1.5rem;">Rp{{ number_format($grandTotal, 0, ',', '.') }}
                            </span>
                        </div>

                        <button id="pay-button" class="btn btn-lg btn-success px-4">
                            <i class="bi bi-credit-card"></i> Bayar Sekarang
                        </button>

                        <p class="text-muted mt-3">
                            Transaksi Anda akan diproses melalui Midtrans Payment Gateway.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function() {
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    window.location.href = "{{ route('booking.success') }}?order_id=" + result.order_id;
                },
                onPending: function(result) {
                    window.location.href = "{{ route('booking.pending') }}?order_id=" + result.order_id;
                },
                onError: function(result) {
                    window.location.href = "{{ route('booking.error') }}";
                }
            });
        };
    </script>
@endsection
