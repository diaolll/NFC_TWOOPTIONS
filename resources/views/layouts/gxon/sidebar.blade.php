<aside class="app-menubar" id="appMenubar">
    <div class="app-navbar-brand">
        {{-- LOGO --}}
        <a class="navbar-brand-logo" href="{{ route('dashboard') }}">
            <img src="{{ asset('assets/images/logo.svg') }}" alt="{{ config('app.name') }}">
        </a>

        {{-- MINI LOGO --}}
        <a class="navbar-brand-mini" href="{{ route('dashboard') }}">
            <span class="text-white fw-bold">NFC</span>
        </a>
    </div>

    <nav class="app-navbar" data-simplebar>
        <ul class="menubar">
            {{-- DASHBOARD --}}
            <li class="menu-item">
                <a class="menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                    href="{{ route('dashboard') }}">
                    <i class="fi fi-rr-home"></i>
                    <span class="menu-label">Dashboard</span>
                </a>
            </li>

            @auth
                @if(auth()->user()->role === 'admin')
                    {{-- ADMIN MENU --}}
                    <li class="menu-label">Admin Menu</li>

                    <li class="menu-item">
                        <a class="menu-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
                            href="{{ route('users.index') }}">
                            <i class="fi fi-rr-users"></i>
                            <span class="menu-label">Data Mahasiswa</span>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a class="menu-link {{ request()->routeIs('attendances.*') ? 'active' : '' }}"
                            href="{{ route('attendances.index') }}">
                            <i class="fi fi-rr-clipboard-list"></i>
                            <span class="menu-label">Data Absensi</span>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a class="menu-link {{ request()->routeIs('nfc.register*') ? 'active' : '' }}"
                            href="{{ route('nfc.register') }}">
                            <i class="fi fi-rr-id-badge"></i>
                            <span class="menu-label">Registrasi Kartu</span>
                        </a>
                    </li>

                    <li class="menu-label">Scanner</li>

                    <li class="menu-item">
                        <a class="menu-link {{ request()->routeIs('qrcode.scan') ? 'active' : '' }}"
                            href="{{ route('qrcode.scan') }}">
                            <i class="fi fi-rr-qrcode"></i>
                            <span class="menu-label">Scan QR Code</span>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a class="menu-link {{ request()->routeIs('nfc.scan') ? 'active' : '' }}"
                            href="{{ route('nfc.scan') }}">
                            <i class="fi fi-rr-nfc"></i>
                            <span class="menu-label">Scan NFC</span>
                        </a>
                    </li>

                @else
                    {{-- MAHASISWA MENU --}}
                    <li class="menu-label">Menu</li>

                    <li class="menu-item">
                        <a class="menu-link {{ request()->routeIs('users.my-attendance') ? 'active' : '' }}"
                            href="{{ route('users.my-attendance') }}">
                            <i class="fi fi-rr-calendar-check"></i>
                            <span class="menu-label">Riwayat Absensi</span>
                        </a>
                    </li>

                    <li class="menu-label">QR Code</li>

                    <li class="menu-item">
                        <a class="menu-link {{ request()->routeIs('qrcode.my-code') ? 'active' : '' }}"
                            href="{{ route('qrcode.my-code') }}">
                            <i class="fi fi-rr-clone"></i>
                            <span class="menu-label">QR Code Saya</span>
                        </a>
                    </li>

                    <!-- Generate QR removed (handled in QR Code Saya view) -->
                @endif
            @endauth

            {{-- ACCOUNT --}}
            <li class="menu-label">Account</li>

            <li class="menu-item">
                <a class="menu-link {{ request()->routeIs('profile*') ? 'active' : '' }}"
                    href="{{ route('profile.edit') }}">
                    <i class="fi fi-rr-user"></i>
                    <span class="menu-label">Profile</span>
                </a>
            </li>

            <li class="menu-item">
                <a class="menu-link" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fi fi-rr-sign-out-alt"></i>
                    <span class="menu-label">Logout</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </nav>
</aside>