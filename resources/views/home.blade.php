@extends('layouts.app')
@section('title', 'Dashboard') {{-- Ini buat title di tab browser --}}
@section('page_title', 'Dashboard') {{-- Ini buat judul yang tampil di halaman --}}
@section('content')
    <style>
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .dashboard-grid:not(:last-child) {
            margin-bottom: 20px;
        }

        .dashboard-card {
            background: linear-gradient(50deg, #4f52ba 0%, rgb(209, 210, 241) 100%);
            border-radius: 16px;
            padding: 20px;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            transition: 0.3s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
        }

        .card-icon {
            font-size: 2rem;
            margin-right: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-content h4 {
            margin: 0;
            font-size: 1rem;
            font-weight: 600;
        }

        .card-content p {
            margin: 5px 0 0;
            font-size: 0.875rem;
            font-weight: 500;
        }
    </style>
    <div class="main">
        <div class="dashboard-grid">
            <div class="dashboard-card">
                <div class="card-icon"><i class="fa-solid fa-earth-americas"></i></div>
                <div class="card-content">
                    <h4>Region</h4>
                    <p>{{ $jumlahRegion }} data</p>
                </div>
            </div>
            <div class="dashboard-card">
                <div class="card-icon"><i class="fa-solid fa-building"></i></div>
                <div class="card-content">
                    <h4>POP</h4>
                    <p>{{ $jumlahJenisSite['POP'] ?? 0 }} data</p>
                </div>
            </div>
            <div class="dashboard-card">
                <div class="card-icon"><i class="fa-solid fa-building-user"></i></div>
                <div class="card-content">
                    <h4>POC</h4>
                    <p>{{ $jumlahJenisSite['POC'] ?? 0 }} data</p>
                </div>
            </div>
        </div>
        <div class="dashboard-grid">
            <div class="dashboard-card">
                <div class="card-icon"><i class="fas fa-tools"></i></div>
                <div class="card-content">
                    <h4>Perangkat</h4>
                    <p>{{ $jumlahPerangkat }} data</p>
                </div>
            </div>
            <div class="dashboard-card">
                <div class="card-icon"><i class="fas fa-building"></i></div>
                <div class="card-content">
                    <h4>Fasilitas</h4>
                    <p>{{ $jumlahFasilitas }} data</p>
                </div>
            </div>
            <div class="dashboard-card">
                <div class="card-icon"><i class="fas fa-ruler"></i></div>
                <div class="card-content">
                    <h4>Alat Ukur</h4>
                    <p>{{ $jumlahAlatUkur }} data</p>
                </div>
            </div>
            <div class="dashboard-card">
                <div class="card-icon"><i class="fas fa-network-wired"></i></div>
                <div class="card-content">
                    <h4>Jaringan</h4>
                </div>
            </div>
        </div>
    </div>
@endsection