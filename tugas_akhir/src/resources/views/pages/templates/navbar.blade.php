<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">SHELLA BEAUTY SALON</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('home') }}">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('services') }}">Layanan</a>
                </li>
                {{-- <li class="nav-item">
                    <a class="nav-link" href="#about">Tentang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#contact">Kontak</a>
                </li> --}}
                @if (Auth::check())



                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            {{-- Foto profile --}}
                            @if (Auth::user()->profile_photo_path)
                                <img src="{{ asset('storage/images/profile/' . Auth::user()->profile_photo_path) }}"
                                    alt="{{ Auth::user()->name }}" class="rounded-circle" width="30" height="30">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}"
                                    alt="{{ Auth::user()->name }}" class="rounded-circle" width="30" height="30">
                            @endif
                        </a>
                        <ul class="dropdown-menu">
                            @if (Auth::user()->role == 'user')
                                <li><a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li><a class="dropdown-item" href="{{ route('user.transaction-history') }}">Riwayat
                                        Transaksi</a></li>
                            @endif
                            @if (Auth::user()->role == 'admin')
                                <li><a class="dropdown-item" href="{{ route('dashboard.services.index') }}">Layanan</a>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('dashboard.workers.index') }}">Pekerja</a>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('dashboard.bookings.index') }}">Booking</a>
                            @endif
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <span class="align-middle">Log Out</span>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>

                    {{-- Keranjang --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cart.index') }}">
                            <i class="bi bi-bag"></i>
                            {{-- Hitung jumlah item di keranjang --}}
                            @php
                                // cek user yang login
                                $userId = Auth::id();
                                // ambil data keranjang berdasarkan user yang login
                                $cartCount = \App\Models\Cart::where('user_id', $userId)->count();

                            @endphp
                            @if ($cartCount > 0)
                                {{-- Tampilkan badge jika ada item di keranjang --}}
                                <span class="badge bg-danger">{{ $cartCount }}</span>
                            @endif
                        </a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="btn btn-sm btn-outline-primary mt-1 me-1" href="{{ route('login') }}">Masuk</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-sm btn-success mt-1" href="{{ route('register') }}">Daftar</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
