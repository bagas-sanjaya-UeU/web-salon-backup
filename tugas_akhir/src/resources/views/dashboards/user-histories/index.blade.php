@extends('dashboards.templates.base')

@section('title', 'Dashboard Histori Booking')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title
                        ">Data Histori Booking </h4>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="users-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal Booking</th>
                                        <th>Waktu Booking</th>
                                        <th>Layanan</th>
                                        <th>Total</th>
                                        <th>Status Transaksi</th>
                                        <th>Terbayar</th>
                                        <th>Batalkan Transaksi</th>
                                        <th>Status Pengembalian</th>
                                        <th>Rating</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($bookings as $index => $booking)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ \Carbon\Carbon::parse($booking->booking_date)->locale('id')->translatedFormat('l, d F Y') }}

                                            </td>
                                            <td>{{ $booking->booking_time }}</td>
                                            <td>
                                                {{ $booking->services->pluck('service_name')->implode(', ') }}
                                            </td>
                                            <td>Rp{{ number_format($booking->total_price) }}</td>
                                            <td {{-- 'pending', 'confirmed', 'canceled', 'completed', 'refunded' --}}
                                                class="{{ $booking->status == 'confirmed' ? 'text-success' : ($booking->status == 'pending' ? 'text-warning' : ($booking->status == 'canceled' ? 'text-danger' : ($booking->status == 'completed' ? 'text-success' : 'text-secondary'))) }}">
                                                {{ $booking->status == 'pending' ? 'Menunggu Konfirmasi' : ($booking->status == 'confirmed' ? 'Diterima' : ($booking->status == 'canceled' ? 'Dibatalkan' : ($booking->status == 'completed' ? 'Selesai' : 'Pengembalian Dana'))) }}

                                            <td
                                                class="{{ $booking->payment_status == 'paid' ? 'text-success' : ($booking->payment_status == 'unpaid' ? 'text-danger' : 'text-warning') }}
                                            }}">
                                                {{ ucfirst($booking->payment_status) == 'unpaid' ? 'Belum Dibayar' : ($booking->payment_status == 'paid' ? 'Dibayar' : 'Menunggu Pembayaran') }}
                                            </td>
                                            <td>
                                                @if (($booking->status == 'confirmed' || $booking->status == 'pending') && $booking->payment_status == 'paid')
                                                    @if ($booking->user->bank_name == null)
                                                        <!-- Button trigger modal -->
                                                        <button type="button" class="btn btn-danger btn-sm mb-2"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#cancelModal{{ $booking->id }}">
                                                            <i class="bx bx-x"></i>
                                                        </button>
                                                    @else
                                                        <form
                                                            action="{{ route('user.transaction-history.cancel', $booking->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger btn-sm"
                                                                onclick="return confirm('Apakah Anda yakin ingin membatalkan?')"><i
                                                                    class="bx bx-x"></i></button>
                                                        </form>
                                                    @endif
                                                @elseif ($booking->status == 'canceled')
                                                    <span class="text-success">
                                                        <i class="bx bx-check"></i>
                                                        <span class="text-success">Dana sudah dikembalikan</span>
                                                    </span>
                                                @elseif ($booking->status == 'completed')
                                                    <span class="text-success">
                                                        <i class="bx bx-check"></i>
                                                        <span class="text-success">Selesai</span>
                                                    </span>
                                                @elseif ($booking->status == 'refunded')
                                                    <span class="text-success">
                                                        <i class="bx bx-check"></i>
                                                        <span class="text-success">Dana sudah dikembalikan</span>
                                                    </span>
                                                @else
                                                    <span class="text-secondary">
                                                        @if ($booking->payment_status == 'unpaid')
                                                            <i class="bx bx-x"></i>
                                                            <span class="text-secondary">Belum Dibayar</span>
                                                        @else
                                                            <i class="bx bx-x"></i>
                                                            <span class="text-secondary">Tidak bisa dikembalikan</span>
                                                        @endif
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($booking->cancel_status == 'requested')
                                                    <span class="text-warning">Menunggu Konfirmasi Admin</span>
                                                @elseif($booking->cancel_status == 'approved')
                                                    <span class="text-success">Dana telah dikembalikan</span>
                                                @elseif($booking->cancel_status == 'rejected')
                                                    <span class="text-danger">Ditolak</span>
                                                @else
                                                    <span class="text-secondary">Tidak Ada Pembatalan</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($booking->status == 'completed' && $booking->rating == null)
                                                    <form
                                                        action="{{ route('user.transaction-history.rating', $booking->id) }}"
                                                        method="POST" class="rating-form d-inline">
                                                        @csrf
                                                        <div class="rating-stars d-flex gap-1 align-items-center"
                                                            data-booking-id="{{ $booking->id }}">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                <button type="submit" name="rating"
                                                                    value="{{ $i }}"
                                                                    class="star-btn btn p-0 border-0 bg-transparent"
                                                                    style="font-size: 1.5rem; color: #ccc; line-height: 1;">
                                                                    ★
                                                                </button>
                                                            @endfor
                                                        </div>

                                                    </form>
                                                @elseif($booking->rating != null)
                                                    <div class="d-flex gap-1 text-warning" style="font-size: 1.3rem;">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <i
                                                                class="bx {{ $i <= $booking->rating ? 'bxs-star' : 'bx-star' }}"></i>
                                                        @endfor
                                                    </div>
                                                @else
                                                    <span class="text-secondary">Belum Dinilai</span>
                                                @endif


                                            </td>
                                            <td>
                                                <a href="{{ route('user.transaction-history.detail', $booking->id) }}"
                                                    class="btn btn-info btn-sm"><i class="bx bx-show"></i> Detail</a>
                                            </td>
                                        </tr>

                                        <!-- Modal Form Bank -->
                                        <div class="modal fade" id="cancelModal{{ $booking->id }}" tabindex="-1"
                                            aria-labelledby="cancelModal{{ $booking->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="cancelModal{{ $booking->id }}">
                                                            Set Data Bank
                                                        </h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form
                                                            action="{{ route('user.transaction-history.cancel', $booking->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <div class="mb-3">
                                                                <label for="name" class="form-label">Nama
                                                                    Bank</label>
                                                                <input type="text" class="form-control" id="bank_name"
                                                                    name="bank_name" required>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="bank_account_name" class="form-label">Nama
                                                                    Pemilik Rekening</label>
                                                                <input type="text" class="form-control"
                                                                    id="bank_account_name" name="bank_account_name"
                                                                    required>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="account_number" class="form-label">Nomor
                                                                    Rekening</label>
                                                                <input type="text" class="form-control"
                                                                    id="account_number" name="bank_account_number" required>
                                                            </div>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada data</td>
                                        </tr>
                                    @endforelse

                                </tbody>


                            </table>


                        </div>
                        {{-- <a href="{{ route('dashboard.transaction.create') }}" class="btn btn-primary">Tambah Data</a> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>
    <script>
        $(document).ready(function() {
            $('#users-table').DataTable({
                "autoWidth": false, // Prevents automatic column resizing issues
                "columnDefs": [{
                        "targets": "_all",
                        "defaultContent": "-"
                    } // Fallback for missing data
                ]
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ratingForms = document.querySelectorAll('.rating-form');

            ratingForms.forEach(form => {
                const stars = form.querySelectorAll('.star-btn');

                stars.forEach((btn, index) => {
                    btn.addEventListener('mouseenter', () => {
                        // Highlight stars saat hover
                        for (let i = 0; i <= index; i++) {
                            stars[i].style.color = '#ffc107';
                        }
                        for (let i = index + 1; i < stars.length; i++) {
                            stars[i].style.color = '#ccc';
                        }
                    });

                    btn.addEventListener('mouseleave', () => {
                        // Reset warna bintang jika belum diklik
                        stars.forEach(s => s.style.color = '#ccc');
                    });

                    // Saat klik, kirim form (tanpa Ajax, langsung POST)
                    btn.addEventListener('click', () => {
                        // Tetap highlight saat submit
                        for (let i = 0; i <= index; i++) {
                            stars[i].style.color = '#ffc107';
                        }
                        form.submit();
                    });
                });
            });
        });
    </script>
@endpush
