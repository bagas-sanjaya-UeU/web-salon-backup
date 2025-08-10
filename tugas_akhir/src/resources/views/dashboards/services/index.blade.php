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
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Data Layanan</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('dashboard.services.store') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="service_name" class="form-label">Nama Layanan</label>
                                                <input type="text" class="form-control" id="service_name"
                                                    name="service_name">
                                            </div>
                                            <div class="mb-3">
                                                <label for="image" class="form-label">Gambar</label>
                                                <input type="file" class="form-control" id="image" name="image"
                                                    accept="image/*">
                                            </div>
                                            <div class="mb-3">
                                                <label for="description" class="form-label">Deskripsi</label>
                                                <textarea class="form-control editor" id="description" name="description" rows="3"></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label for="price" class="form-label">Harga</label>
                                                <input type="number" min="0" class="form-control" id="price"
                                                    name="price">
                                                    <p>Tips: Tambah min 5K Sebagai PPN pada saat set Harga</p>
                                                    <p>Cth: Harga layanan 50.000 + 5.000 Jadi 55.000</p>
                                            </div>
                                            <div class="mb-3">
                                                <label for="is_home_service" class="form-label">Home Service</label>
                                                <select class="form-select" aria-label="Default select example"
                                                    name="is_home_service">
                                                    <option value="1" selected>Ya</option>
                                                    <option value="0">Tidak</option>
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
                                        <th>Gambar</th>
                                        <th>Nama</th>
                                        <th>Deskripsi</th>
                                        <th>Harga</th>
                                        <th>Home Service</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($services as $service)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                @if ($service->image)
                                                    <img src="{{ asset('storage/images/services/' . $service->image) }}"
                                                        alt="Gambar" class="img-fluid"
                                                        style="width: 100px; height: 100px;">
                                                @else
                                                    <img src="{{ asset('images/default.png') }}" alt="Gambar"
                                                        class="img-fluid" style="width: 100px; height: 100px;">
                                                @endif
                                            </td>
                                            <td>{{ $service->service_name }}</td>
                                            <td>{!! $service->description !!}</td>
                                            <td>Rp{{ number_format($service->price) }}</td>
                                            <td>{{ $service->is_home_service ? 'Ya' : 'Tidak' }}</td>

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
                                                            Layanan</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form
                                                            action="{{ route('dashboard.services.update', $service->id) }}"
                                                            method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            @method('put')
                                                            <div class="mb-3">
                                                                <label for="service_name" class="form-label">Nama
                                                                    Layanan</label>
                                                                <input type="text" class="form-control"
                                                                    id="service_name" name="service_name"
                                                                    value="{{ $service->service_name }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="image" class="form-label">Gambar</label>
                                                                <input type="file" class="form-control" id="image"
                                                                    name="image" accept="image/*">
                                                                @if ($service->image)
                                                                    <img src="{{ asset('storage/images/services/' . $service->image) }}"
                                                                        alt="Gambar" class="img-fluid mt-2"
                                                                        style="width: 100px; height: 100px;">
                                                                @endif
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="description"
                                                                    class="form-label">Deskripsi</label>
                                                                <textarea class="form-control editor" id="description" name="description" rows="3">{!! $service->description !!}</textarea>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="price" class="form-label">Harga</label>
                                                                <input type="number" min="0" class="form-control"
                                                                    id="price" name="price"
                                                                    value="{{ $service->price }}">
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="is_home_service" class="form-label">Home
                                                                    Service</label>
                                                                <select class="form-select"
                                                                    aria-label="Default select example"
                                                                    name="is_home_service">
                                                                    <option value="1"
                                                                        {{ $service->is_home_service ? 'selected' : '' }}>
                                                                        Ya</option>
                                                                    <option value="0"
                                                                        {{ !$service->is_home_service ? 'selected' : '' }}>
                                                                        Tidak</option>
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
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>


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
        function initEditor(el) {
            ClassicEditor
                .create(el, {
                    toolbar: [
                        'undo', 'redo',
                        '|', 'heading',
                        '|', 'bold', 'italic',
                        '|', 'numberedList', 'bulletedList',
                        '|', 'blockQuote'
                    ],
                    removePlugins: [
                        'Link',
                        'Image', 'ImageToolbar', 'ImageCaption', 'ImageStyle',
                        'ImageUpload', 'CKFinder', 'MediaEmbed', 'EasyImage'
                    ]
                })
                .catch(error => {
                    console.error('CKEditor error:', error);
                });
        }

        // Inisialisasi editor saat dokumen ready
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.editor').forEach(el => {
                if (!el.classList.contains('ckeditor-applied')) {
                    initEditor(el);
                    el.classList.add('ckeditor-applied'); // biar gak diinisialisasi 2x
                }
            });
        });

        // Optional: Jika kamu pakai modal bootstrap
        const editModals = document.querySelectorAll('.modal');
        editModals.forEach(modal => {
            modal.addEventListener('shown.bs.modal', () => {
                modal.querySelectorAll('.editor').forEach(el => {
                    if (!el.classList.contains('ckeditor-applied')) {
                        initEditor(el);
                        el.classList.add('ckeditor-applied');
                    }
                });
            });
        });
    </script>
@endpush
