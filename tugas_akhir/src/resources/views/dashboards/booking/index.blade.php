@extends('dashboards.templates.base')

@section('title', 'Dashboard Booking')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title
                        ">Data Booking </h4>
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
                                        <th>Nama</th>
                                        <th>Tanggal</th>
                                        <th>Waktu</th>
                                        <th>Layanan</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Terbayar</th>
                                        <th>Permohonan Pengembalian</th>
                                        <th>Rating</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($bookings as $index => $booking)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $booking->user->role == 'admin' ? $booking->name : $booking->user->name }}
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($booking->booking_date)->locale('id')->translatedFormat('l, d F Y') }}

                                            </td>
                                            <td>{{ $booking->booking_time }}</td>
                                            <td>
                                                {{ $booking->services->pluck('service_name')->implode(', ') }}
                                            </td>
                                            <td>Rp{{ number_format($booking->total_price) }}</td>
                                            <td>
                                                {{ $booking->status == 'pending' ? 'Menunggu Konfirmasi' : ($booking->status == 'confirmed' ? 'Diterima' : ($booking->status == 'canceled' ? 'Dibatalkan' : ($booking->status == 'completed' ? 'Selesai' : 'Pengembalian Dana'))) }}
                                            </td>
                                            <td
                                                class="{{ $booking->payment_status == 'paid' ? 'text-success' : ($booking->payment_status == 'unpaid' ? 'text-danger' : 'text-warning') }}
                                            }}">
                                                {{ ucfirst($booking->payment_status) == 'unpaid' ? 'Belum Dibayar' : ($booking->payment_status == 'paid' ? 'Dibayar' : 'Menunggu Pembayaran') }}
                                            </td>

                                            <td>
                                                @if ($booking->status == 'canceled')
                                                    @if ($booking->cancel_status == 'requested')
                                                        <!-- Button trigger modal -->
                                                        <button type="button" class="btn btn-warning btn-sm mb-2"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#refundModal{{ $booking->id }}">
                                                            Meminta Pengembalian. Mohon dikirim melalui Rekening berikut
                                                            <br>
                                                            <i class='bx bxs-hand-up'></i>
                                                        </button>
                                                    @elseif($booking->cancel_status == 'approved')
                                                        <span class="badge bg-success">Disetujui</span>
                                                    @elseif($booking->cancel_status == 'rejected')
                                                        <span class="badge bg-danger">Ditolak</span>
                                                    @else
                                                        <span class="badge bg-secondary">Tidak Ada</span>
                                                    @endif
                                                @elseif ($booking->status == 'refunded')
                                                    <span class="badge bg-success">Pengembalian Dana Berhasil</span>
                                                @else
                                                    <span class="badge bg-secondary">Tidak Ada</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if ($booking->rating)
                                                    <div class="d-flex gap-1 text-warning" style="font-size: 1.3rem;">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <i
                                                                class="bx {{ $i <= $booking->rating ? 'bxs-star' : 'bx-star' }}"></i>
                                                        @endfor
                                                    </div>
                                                @else
                                                    <span class="badge bg-secondary">Belum Dinilai</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if ($booking->payment_status == 'paid' && (($booking->status == 'confirmed' || $booking->status == 'completed') && !$booking->worker_id))
                                                    <!-- Button trigger modal -->
                                                    <button type="button" class="btn btn-primary mb-2"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#workerModal{{ $booking->id }}">
                                                        Set Pekerja
                                                    </button>
                                                @endif
                                                <a href="{{ route('dashboard.bookings.detail', $booking->id) }}"
                                                    class="btn btn-info btn-sm"><i class="bx bx-show"></i></a>
                                            </td>
                                        </tr>

                                        <!-- Modal Set pekerja -->
                                        <div class="modal fade" id="workerModal{{ $booking->id }}" tabindex="-1"
                                            aria-labelledby="workerModal{{ $booking->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="workerModal{{ $booking->id }}">
                                                            Set Data Pekerja
                                                        </h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form
                                                            action="{{ route('dashboard.bookings.setWorker', $booking->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <div class="mb-3">
                                                                <label for="name" class="form-label">Nama
                                                                    Pekerja</label>
                                                                <select class="form-select"
                                                                    aria-label="Default select example" name="worker_id"
                                                                    required>

                                                                    @foreach ($workers as $worker)
                                                                        <option value="{{ $worker->id }}">
                                                                            {{ $worker->user->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
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
                                        <!-- Modal Pengembalian -->
                                        <div class="modal fade" id="refundModal{{ $booking->id }}" tabindex="-1"
                                            aria-labelledby="refundModal{{ $booking->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="refundModal{{ $booking->id }}">
                                                            Permohonan Pengembalian Dana
                                                        </h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Apakah Anda yakin ingin mengajukan permohonan pengembalian untuk
                                                            booking ini?</p>

                                                        <p>Silakan kirimkan bukti transfer ke rekening berikut:</p>
                                                        <p>Bank: {{ $booking->user->bank_name }}</p>
                                                        <p>Nomor Rekening: {{ $booking->user->bank_account_number }}</p>
                                                        <p>Atas Nama: {{ $booking->user->bank_account_name }}</p>
                                                        <p>Jumlah: Rp{{ number_format($booking->total_price) }}</p>

                                                        <form
                                                            action="{{ route('dashboard.bookings.refundTrigger', $booking->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <div class="mb-3">
                                                                <label for="name" class="form-label">
                                                                    Status Pengembalian</label>
                                                                </label>
                                                                <select class="form-select"
                                                                    aria-label="Default select example" name="cancel_status"
                                                                    required>
                                                                    <option value="approved">Disetujui</option>
                                                                    <option value="rejected">Ditolak</option>
                                                                </select>
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
@endpush
