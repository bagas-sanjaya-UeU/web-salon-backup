@extends('dashboards.templates.base')

@section('title', 'Dashboard worker')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title
                        ">Data Pekerja </h4>
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
                            Tambah Data Pekerja
                        </button>

                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal"
                            data-bs-target="#changeRoleModal">
                            Set Pengguna Ke Pekerja
                        </button>


                        <!-- Modal Tambah -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Data Pekerja</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('dashboard.workers.store') }}" method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Nama Pekerja</label>
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
                                                    <option value="worker" selected>Pekerja</option>
                                                </select>
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

                        <!-- Modal Set pekerja -->
                        <div class="modal fade" id="changeRoleModal" tabindex="-1" aria-labelledby="changeRoleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="changeRoleModal">Set Data Pekerja</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('dashboard.workers.changeRole') }}" method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Nama Pekerja</label>
                                                <select class="form-select" aria-label="Default select example"
                                                    name="user_id">

                                                    @foreach ($users as $user)
                                                        <option value="{{ $user->id }}">{{ $user->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="role" class="form-label">Role</label>
                                                {{-- select --}}
                                                <select class="form-select" aria-label="Default select example"
                                                    name="role">
                                                    <option value="worker" selected>Pekerja</option>
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




                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="users-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>No Hp</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($workers as $worker)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $worker->user->name }}</td>
                                            <td>{{ $worker->user->phone }}</td>
                                            <td>{{ $worker->user->role }}</td>
                                            <td>
                                                @if ($worker->availability_status == 'available')
                                                    <span class="badge bg-success">Tersedia</span>
                                                @elseif ($worker->availability_status == 'unavailable')
                                                    <span class="badge bg-danger">Tidak Tersedia</span>
                                                @else
                                                    <span class="badge bg-secondary">Sedang Bekerja</span>
                                                @endif
                                            </td>

                                            <td>

                                                <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                                    data-bs-target="#editModal{{ $worker->id }}">
                                                    Edit
                                                </button>

                                                <form action="{{ route('dashboard.workers.destroy', $worker->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('delete')
                                                    <button class="btn btn-danger"
                                                        onclick="return confirm('Apakah anda yakin?')">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>

                                        <!-- Modal Edit -->
                                        <div class="modal fade" id="editModal{{ $worker->id }}" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Data
                                                            Pekerja</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form
                                                            action="{{ route('dashboard.workers.update', $worker->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('put')
                                                            <div class="mb-3">
                                                                <label for="name" class="form-label">Nama</label>
                                                                <input type="text" class="form-control" id="name"
                                                                    name="name" value="{{ $worker->user->name }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="phone_number" class="form-label">No Hp</label>
                                                                <input type="number" min="0" class="form-control"
                                                                    id="phone" name="phone"
                                                                    value="{{ $worker->user->phone }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="email" class="form-label">Email</label>
                                                                <input type="text" class="form-control" id="email"
                                                                    name="email" value="{{ $worker->user->email }}">
                                                            </div>


                                                            <div class="mb-3">
                                                                <label for="role" class="form-label">Role</label>
                                                                {{-- select --}}
                                                                <select class="form-select"
                                                                    aria-label="Default select example" name="role">
                                                                    <option value="worker" selected>
                                                                        Pekerja</option>
                                                                </select>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="availability_status" class="form-label">Siap
                                                                    Kerja</label>
                                                                {{-- select --}}
                                                                <select class="form-select"
                                                                    aria-label="Default select example"
                                                                    name="availability_status">
                                                                    <option value="available"
                                                                        {{ $worker->availability_status == 'available' ? 'selected' : '' }}>
                                                                        Tersedia</option>
                                                                    <option value="unavailable"
                                                                        {{ $worker->availability_status == 'unavailable' ? 'selected' : '' }}>
                                                                        Tidak Tersedia</option>
                                                                    <option value="working"
                                                                        {{ $worker->availability_status == 'working' ? 'selected' : '' }}>
                                                                        Bekerja</option>
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
