@extends('layouts.app')
@section('title', 'Dasbor') 
@section('page_title', 'Dasbor') 
@section('content')
    <div class="main">
        @if(auth()->user()->role == '1' || auth()->user()->role == '2')
            <div class="card-section" style="margin-top: 20px;">
                <div class="card-item" @if(auth()->user()->role == '1')
                onclick="window.location='{{ route('dataregion.index') }}'" style="cursor: pointer;" @endif>
                    <div class="card-icon"><i class="fa-solid fa-earth-americas"></i></div>
                    <div class="card-content">
                        <h4>Region</h4>
                        <p>{{ $jumlahRegion }} data</p>
                    </div>
                </div>

                <div class="card-item" @if(auth()->user()->role == '1')
                onclick="window.location='{{ route('dataregion.index') }}'" style="cursor: pointer;" @endif>
                    <div class="card-icon"><i class="fa-solid fa-building"></i></div>
                    <div class="card-content">
                        <h4>POP</h4>
                        <p>{{ $jumlahJenisSite['POP'] ?? 0 }} data</p>
                    </div>
                </div>
                <div class="card-item" @if(auth()->user()->role == '1')
                onclick="window.location='{{ route('dataregion.index') }}'" style="cursor: pointer;" @endif>
                    <div class="card-icon"><i class="fa-solid fa-building-user"></i></div>
                    <div class="card-content">
                        <h4>POC</h4>
                        <p>{{ $jumlahJenisSite['POC'] ?? 0 }} data</p>
                    </div>
                </div>
            </div>
        @endif
        <div class="card-section" style="margin-top: 20px;">
            <div class="card-item" onclick="window.location='{{ route('perangkat.index') }}'" style="cursor: pointer;">
                <div class="card-icon"><i class="fas fa-tools"></i></div>
                <div class="card-content">
                    <h4>Perangkat</h4>
                    <p>{{ $jumlahPerangkat }} data</p>
                </div>
            </div>

            <div class="card-item" onclick="window.location='{{ route('fasilitas.index') }}'" style="cursor: pointer;">
                <div class="card-icon"><i class="fas fa-warehouse"></i></div>
                <div class="card-content">
                    <h4>Fasilitas</h4>
                    <p>{{ $jumlahFasilitas }} data</p>
                </div>
            </div>

            <div class="card-item" onclick="window.location='{{ route('alatukur.index') }}'" style="cursor: pointer;">
                <div class="card-icon"><i class="fas fa-ruler"></i></div>
                <div class="card-content">
                    <h4>Alat Ukur</h4>
                    <p>{{ $jumlahAlatUkur }} data</p>
                </div>
            </div>

            <div class="card-item" onclick="window.location='{{ route('jaringan.index') }}'" style="cursor: pointer;">
                <div class="card-icon"><i class="fas fa-project-diagram"></i></div>
                <div class="card-content">
                    <h4>Jaringan</h4>
                    <p>{{ $jumlahJaringan }} data</p>
                </div>
            </div>
        </div>
    </div>
@endsection