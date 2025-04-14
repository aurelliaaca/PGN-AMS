<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> -->
</head>

<link rel="stylesheet" href="{{ asset('css/app.css') }}">

<body>
    <aside class="sidebar">
        <div class="sidebar-title">
            <img src="{{ asset('img/pgncom.png') }}" alt="logo" />
            <h2>AMS</h2>
        </div>
        <ul class="sidebar-links">
            <h4>
                <span>Menu</span>
                <div class="menu-separator"></div>
            </h4>
            <li>
                <a href="{{ route('home') }}">
                    <span class="icon"><i class="fas fa-tachometer-alt"></i></span>
                    <span class="text">Dasbor</span>
                </a>
            </li>
            <li>
                <a href="{{ route('data') }}">
                    <span class="icon"><i class="fas fa-database"></i></span>
                    <span class="text">Data</span>
                </a>
            </li>
            <li>
                <a href="{{ route('rack.index') }}">
                    <span class="icon"><i class="fas fa-server"></i></span>
                    <span class="text">Rack</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <span class="icon"><i class="fas fa-image"></i></span>
                    <span class="text">Semantik</span>
                </a>
            </li>
            <li>
                <a href="{{ route('histori.index') }}">
                    <span class="icon"><i class="fas fa-history"></i></span>
                    <span class="text">Histori</span>
                </a>
            </li>
            @if(auth()->user()->role == '1' || auth()->user()->role == '2')
                <h4>
                    <span>Aset</span>
                    <div class="menu-separator"></div>
                </h4>
                <li>
                    <a href="{{ route('perangkat.index') }}">
                        <span class="icon"><i class="fas fa-tools"></i></span>
                        <span class="text">Perangkat</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('perangkat.index') }}">
                        <span class="icon"><i class="fas fa-warehouse"></i></span>
                        <span class="text">Fasilitas</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="icon"><i class="fas fa-ruler"></i></span>
                        <span class="text">Alat Ukur</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="icon"><i class="fas fa-network-wired"></i></span>
                        <span class="text">Jaringan</span>
                    </a>
                </li>
            @endif
            <h4>
                <span>VMS</span>
                <div class="menu-separator"></div>
            </h4>
            <li>
                <a href="{{ auth()->user()->role == 1 ? route('verifikasi.superadmin.index') : route('verifikasi.user.index') }}">
                    <span class="icon"><i class="fas fa-tools"></i></span>
                    <span class="text">Verifikasi</span>
                </a>
            </li>

            <h4>
                <span>Akun</span>
                <div class="menu-separator"></div>
            </h4>
            <li>
                <a href="#">
                    <span class="icon"><i class="fas fa-user-circle"></i></span>
                    <span class="text">Profil</span>
                </a>
            </li>

            <li>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit">
                        <span class="icon"><i class="fas fa-sign-out-alt" style="margin-left:5px"></i></span>
                        <span class="text" style="margin-left:-2px">Keluar</span>
                    </button>
                </form>
            </li>
        </ul>

        <div class="user-account">
            <div class="user-profile">
                <img src="{{ asset('img/profile-default.png') }}" alt="Profile Image" />
                <div class="user-detail">
                    <h3>{{ auth()->user()->name }}</h3>
                    @php
                        $roleText = [
                            1 => 'Superadmin',
                            2 => 'Admin',
                            3 => 'Guest',
                        ][auth()->user()->role] ?? 'Unknown'; // Default 'Unknown' jika role tidak dikenali
                    @endphp
                    <span>{{ $roleText }}</span>
                </div>
            </div>
        </div>
    </aside>

    <header class="header">
        <h1>@yield('page_title')</h1>
    </header>

    <main class="main">
        @yield('content')
    </main>

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    
    <!-- Script untuk Modal -->
    <script>
        function openModal(id) {
            document.getElementById(id).style.display = "block";
        }

        function closeModal(id) {
            document.getElementById(id).style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = "none";
            }
        }
    </script>

    <!-- Script untuk SweetAlert -->
    <script>
        // Tampilkan SweetAlert jika ada pesan
        @if(session('swal'))
            Swal.fire({
                icon: '{{ session('swal')['icon'] }}',
                title: '{{ session('swal')['title'] }}',
                text: '{{ session('swal')['text'] }}',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        @endif
    </script>
</body>
