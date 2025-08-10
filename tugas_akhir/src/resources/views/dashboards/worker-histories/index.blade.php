@extends('dashboards.templates.base')

@section('title', 'Dashboard Histori Pekerja')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Riwayat Pekerjaan</h4>
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
                                        <th>Nama Pekerja</th>
                                        <th>No HP</th>
                                        <th>Customer</th>
                                        <th>Tanggal</th>
                                        <th>Waktu</th>
                                        <th>Status Kerja</th>
                                        <th>Status Konfirmasi</th>
                                        @if (auth()->user()->role == 'worker')
                                            <th>Centang Selesai</th>
                                        @endif
                                        <th>Rating</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($histories as $history)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $history->worker->user->name }}</td>
                                            <td>{{ $history->worker->user->phone }}</td>
                                            <td>{{ $history->booking->user->role == 'admin' ? $history->booking->name : $history->booking->user->name }}
                                            </td>

                                            <td>{{ \Carbon\Carbon::parse($history->booking->booking_date)->translatedFormat('l, d F Y') }}
                                            </td>
                                            <td>{{ $history->booking->booking_time }}</td>
                                            <td>
                                                @if ($history->status == 'completed')
                                                    <span class="badge bg-success">Selesai</span>
                                                @elseif ($history->status == 'ongoing')
                                                    <span class="badge bg-warning text-dark">Sedang Dikerjakan</span>
                                                @else
                                                    <span class="badge bg-danger">Dibatalkan</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($history->confirmation_status == 'confirmed')
                                                    <span class="badge bg-primary">Dikonfirmasi</span>
                                                @else
                                                    <span class="badge bg-secondary">Belum Dikonfirmasi</span>
                                                @endif
                                            </td>

                                            @if (auth()->user()->role == 'worker')
                                                <td>
                                                    @if ($history->confirmation_status == 'pending')
                                                        <form
                                                            action="{{ route('dashboard.transaction-history.confirm', $history->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success btn-sm"
                                                                onclick="return confirm('Apakah Anda yakin ingin mengonfirmasi pekerjaan ini?')">
                                                                <i class="bi bi-check"></i> Konfirmasi
                                                            </button>
                                                        </form>
                                                    @else
                                                        <span class="badge bg-success"><i
                                                                class='bx bx-check-circle'></i></span>
                                                    @endif
                                                </td>
                                            @endif
                                            <td>
                                                @if ($history->booking->rating != null)
                                                    <div class="d-flex gap-1 text-warning" style="font-size: 1.3rem;">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <i
                                                                class="bx {{ $i <= $history->booking->rating ? 'bxs-star' : 'bx-star' }}"></i>
                                                        @endfor
                                                    </div>
                                                @else
                                                    <span class="badge bg-secondary">Belum Dinilai</span>
                                                @endif
                                            </td>

                                            <td>
                                                <a href="{{ route('dashboard.transaction-history.detail', $history->id) }}"
                                                    class="btn btn-info btn-sm">
                                                    <i class="bi bi-eye"></i> Detail
                                                </a>
                                            </td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">Tidak ada data histori</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
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
                "autoWidth": false,
                "columnDefs": [{
                    "targets": "_all",
                    "defaultContent": "-"
                }]
            });
        });
    </script>
@endpush
