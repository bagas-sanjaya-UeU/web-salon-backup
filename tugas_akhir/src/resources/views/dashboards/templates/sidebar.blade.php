<!-- Menu -->

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo " style="background-color:rgb(19, 221, 177) !important">
        <a href="{{ route('dashboard') }}" class="app-brand-link">

            <span class="app-brand-text demo menu-text fw-bolder ms-2"
                style="text-transform: uppercase !important; color: white;">Shella Salon</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item mt-2 {{ Route::currentRouteName() == 'dashboard' ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>


        <!-- Components -->
        <li class="menu-header small text-uppercase"><span class="menu-header-text">All Menu</span></li>

        @if (Auth::user()->role == 'admin')
            <li
                class="menu-item {{ Route::currentRouteName() == 'dashboard.bookings.index' ||
                Route::currentRouteName() == 'dashboard.bookings.detail'
                    ? 'active'
                    : '' }}
        ">
                <a href="{{ route('dashboard.bookings.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-box "></i>
                    <div data-i18n="Basic">Booking</div>
                </a>
            </li>

            <li
                class="menu-item {{ Route::currentRouteName() == 'dashboard.services.index' ||
                Route::currentRouteName() == 'dashboard.services.detail'
                    ? 'active'
                    : '' }}
        ">
                <a href="{{ route('dashboard.services.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-box "></i>
                    <div data-i18n="Basic">Layanan</div>
                </a>
            </li>

            <li
                class="menu-item {{ Route::currentRouteName() == 'dashboard.workers.index' ||
                Route::currentRouteName() == 'dashboard.workers.detail'
                    ? 'active'
                    : '' }}
        ">
                <a href="{{ route('dashboard.workers.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-box "></i>
                    <div data-i18n="Basic">Pekerja</div>
                </a>
            </li>

            <li
                class="menu-item {{ Route::currentRouteName() == 'dashboard.booking-menu.index' ||
                Route::currentRouteName() == 'dashboard.booking-menu.detail'
                    ? 'active'
                    : '' }}
        ">
                <a href="{{ route('dashboard.booking-menu.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-box "></i>
                    <div data-i18n="Basic">+ Buat Booking</div>
                </a>
            </li>
        @endif

        @if (Auth::user()->role == 'admin' || Auth::user()->role == 'worker')
            <li
                class="menu-item
            {{ Route::currentRouteName() == 'dashboard.transaction-history.index' ||
            Route::currentRouteName() == 'dashboard.transaction-history.detail'
                ? 'active'
                : '' }} ">
                <a href="{{ route('dashboard.transaction-history.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-box "></i>
                    <div data-i18n="Basic">Histori Pekerja</div>
                </a>
            </li>
        @endif


        @if (Auth::user()->role == 'user')
            <li
                class="menu-item
            {{ Route::currentRouteName() == 'user.transaction-history' ||
            Route::currentRouteName() == 'user.transaction-history.detail'
                ? 'active'
                : '' }} ">
                <a href="{{ route('user.transaction-history') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-box "></i>
                    <div data-i18n="Basic">Riwayat Transaksi</div>
                </a>
            </li>

            <li
                class="menu-item
            {{ Route::currentRouteName() == 'services' || Route::currentRouteName() == 'services' ? 'active' : '' }} ">
                <a href="{{ route('services') }}" class="menu-link">
                    <i class='menu-icon tf-icons bx bx-left-arrow-alt'></i>
                    <div data-i18n="Basic">Kembali ke halaman Layanan</div>
                </a>
            </li>
        @endif





    </ul>
</aside>
<!-- / Menu -->
