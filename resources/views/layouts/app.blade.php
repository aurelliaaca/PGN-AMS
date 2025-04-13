<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
                <a href="#">
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
                <a href="#">
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
                    <a href="#">
                        <span class="icon"><i class="fas fa-tools"></i></span>
                        <span class="text">Perangkat</span>
                    </a>
                </li>
                <li>
                    <a href="#">
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
</body>

<script>
    function openModal(id) {
        document.getElementById(id).style.display = "block";
    }

    function closeModal(id) {
        document.getElementById(id).style.display = "none";
    }

    window.onclick = function (event) {
        document.querySelectorAll(".modal").forEach(modal => {
            if (event.target === modal) {
                modal.style.display = "none";
            }
        });
    }
</script>