@extends('dashboards.templates.base')

@section('title', 'Dashboard Service')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title
                        ">Data Layanan </h4>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal"
                            data-bs-target="#exampleModal">
                            Tambah Data
                        </button>

                        <!-- Modal Tambah -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Data Teknisi</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('dashboard.services.store') }}" method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Nama Teknisi</label>
                                                <input type="text" class="form-control" id="name" name="name">
                                            </div>
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="text" class="form-control" id="email" name="email">
                                            </div>
                                            <div class="mb-3">
                                                <label for="phone_number" class="form-label">No Hp</label>
                                                <input type="text" class="form-control" id="phone_number" name="phone">
                                            </div>
                                            <div class="mb-3">
                                                <label for="role" class="form-label">Role</label>
                                                {{-- select --}}
                                                <select class="form-select" aria-label="Default select example"
                                                    name="role">
                                                    <option value="teknisi" selected>Teknisi</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="address" class="form-label">Alamat</label>
                                                <input type="text" class="form-control" id="address" name="address">
                                            </div>
                                            <div class="mb-3">
                                                <label for="password" class="form-label">Password</label>
                                                <input type="password" class="form-control" id="password" name="password">
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
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="users-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>No Hp</th>
                                        <th>Role</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($services as $service)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $service->name }}</td>
                                            <td>{{ $service->phone }}</td>
                                            <td>{{ $service->role }}</td>

                                            <td>
                                                <a href="{{ route('dashboard.services.detail', $service->id) }}"
                                                    class="btn btn-info">Detail</a>
                                                <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                                    data-bs-target="#editModal{{ $service->id }}">
                                                    Edit
                                                </button>

                                                <form action="{{ route('dashboard.services.destroy', $service->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('delete')
                                                    <button class="btn btn-danger"
                                                        onclick="return confirm('Apakah anda yakin?')">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>

                                        <!-- Modal Edit -->
                                        <div class="modal fade" id="editModal{{ $service->id }}" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Data
                                                            Teknisi</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form
                                                            action="{{ route('dashboard.services.update', $service->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('put')
                                                            <div class="mb-3">
                                                                <label for="name" class="form-label">Nama</label>
                                                                <input type="text" class="form-control" id="name"
                                                                    name="name" value="{{ $service->name }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="phone_number" class="form-label">No Hp</label>
                                                                <input type="text" class="form-control"
                                                                    id="phone_number" name="phone"
                                                                    value="{{ $service->phone }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="email" class="form-label">Email</label>
                                                                <input type="text" class="form-control" id="email"
                                                                    name="email" value="{{ $service->email }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="address" class="form-label">Alamat</label>
                                                                <input type="text" class="form-control" id="address"
                                                                    name="address" value="{{ $service->address }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="role" class="form-label">Role</label>
                                                                {{-- select --}}
                                                                <select class="form-select"
                                                                    aria-label="Default select example" name="role">
                                                                    <option value="teknisi" selected>Teknisi</option>
                                                                </select>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Batal</button>
                                                                <button type="submit"
                                                                    class="btn btn-primary">Simpan</button>
                                                            </div>

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
