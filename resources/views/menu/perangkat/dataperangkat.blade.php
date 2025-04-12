@extends('layouts.app')
@section('title', 'Data Perangkat') {{-- Ini buat title di tab browser --}}
@section('page_title', 'Data Perangkat') {{-- Ini buat judul yang tampil di halaman --}}
@section('content')

<div class="main">
<div class="container">
    <a href="{{ route('brandperangkat.create') }}" class="btn btn-primary mb-3">+ Tambah Brand</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Brand</th>
                <th>Kode Brand</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($brandperangkat as $item)
                <tr>
                    <td>{{ $item->nama_brand }}</td>
                    <td>{{ $item->kode_brand }}</td>
                    <td>
                        <a href="{{ route('brandperangkat.edit', $item->kode_brand) }}" class="btn btn-warning btn-sm">Edit</a>

                        <form action="{{ route('brandperangkat.destroy', $item->kode_brand) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin hapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection