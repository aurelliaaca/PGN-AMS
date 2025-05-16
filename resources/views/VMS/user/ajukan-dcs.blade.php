@extends('layouts.app')

@section('content')
    <div class="form-page-content">
        <h1 style="text-align: center; margin-bottom: 24px; font-size: 28px; font-weight: bold;">
            Ajukan Visit DCS
        </h1>

        <form action="{{ route('dokumen.store') }}" method="POST" id="formAjukanDCS" enctype="multipart/form-data">
            @csrf
            <div style="max-width: 900px; margin: 0 auto;">
                <div class="mb-3">
                    <label>Catatan</label>
                    <input type="text" name="catatan" class="form-control" id="catatan" value="">
                </div>
                <div class="mb-3">
                    <label>Pilih NDA Aktif</label>
                    <select name="verifikasi_nda_id" id="verifikasi_nda_id" class="form-control" required>
                        <option value="">-- Pilih NDA --</option>
                        @foreach($activeNdas as $nda)
                            <option value="{{ $nda->id }}">
                                NDA {{ $nda->nda->perusahaan ? 'Eksternal' : 'Internal' }} {{ $nda->nda->id }} - 
                                Berlaku sampai {{ $nda->masa_berlaku->format('d F Y') }}
                                (Status: {{ $nda->status }})
                            </option>
                        @endforeach
                    </select>
                    @if($activeNdas->isEmpty())
                        <small class="text-danger">Anda tidak memiliki NDA yang aktif. Silahkan ajukan verifikasi NDA terlebih dahulu.</small>
                    @endif
                </div>

                <div style="margin-top: 20px;">
                    <button type="submit" class="btn btn-primary" {{ $activeNdas->isEmpty() ? 'disabled' : '' }}
                        style="background-color: #5a54ea; color: white; border: none; padding: 12px 20px; border-radius: 8px; cursor: pointer;">
                        Kirim
                    </button>
                </div>
            </div>
        </form>
    </div>

    <style>
        .form-control {
            width: 100%;
            padding: 8px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .mb-3 {
            margin-bottom: 1rem;
        }

        .btn-primary:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }
    </style>
@endsection 