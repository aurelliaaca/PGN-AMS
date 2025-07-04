@extends('layouts.app')

@section('title', 'Aset Jaringan')
@section('page_title', 'Aset Jaringan')


@section('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
    <div class="main">
        @if (auth()->user()->role == '1' || auth()->user()->role == '2')
            <div class="button-wrapper" style="margin-top: 20px;">
                <button class="btn btn-primary mb-3" onclick="openModal('modalTambahJaringan')">+ Tambah Jaringan</button>
                <button type="button" class="btn btn-primary mb-3" onclick="openModal('importModal')">Impor Data Jaringan</button>
                <button type="button" class="btn btn-primary mb-3" onclick="openModal('exportModal')">Ekspor Data Jaringan</button>
            </div>

            <form method="GET" action="{{ route('jaringan.index') }}" id="filterForm">
                <div class="filter-container" style="margin-top: 20px;">
                    <select name="kode_region[]" class="select2" multiple data-placeholder="Pilih Region"
                        onchange="document.getElementById('filterForm').submit()">
                        @foreach ($regions as $region)
                            <option value="{{ $region->kode_region }}" {{ in_array($region->kode_region, request('kode_region', [])) ? 'selected' : '' }}>
                                {{ $region->nama_region }}
                            </option>
                        @endforeach
                    </select>

                    <select name="kode_tipejaringan[]" class="select2" multiple data-placeholder="Pilih Tipe Jaringan"
                        onchange="document.getElementById('filterForm').submit()">
                        @foreach ($types as $type)
                            <option value="{{ $type->kode_tipejaringan }}" {{ in_array($type->kode_tipejaringan, request('kode_tipejaringan', [])) ? 'selected' : '' }}>
                                {{ $type->nama_tipejaringan }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        @endif

        <div class="table-responsive" style="margin-top: 20px;">
            <table id="jaringanTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Region</th>
                        <th>Tipe Jaringan</th>
                        <th>Segmen</th>
                        <th>Panjang</th>
                        <th>Jenis Kabel</th>
                        <th>Tipe Kabel</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datajaringan as $jaringan)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $jaringan->region ? $jaringan->region->nama_region : 'Region Tidak Ditemukan' }}</td>
                            <td>{{ $jaringan->tipejaringan ? $jaringan->tipejaringan->nama_tipejaringan : 'Tipe Tidak Ditemukan' }}
                            </td>
                            <td>{{ $jaringan->segmen }}</td>
                            <td>{{ $jaringan->panjang }}</td>
                            <td>{{ $jaringan->jenis_kabel }}</td>
                            <td>{{ $jaringan->tipe_kabel }}</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-eye btn-sm mb-1"
                                        onclick="openModal('modalViewJaringan{{ $jaringan->id_jaringan }}')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if(auth()->user()->role == '1' || auth()->user()->role == '2')
                                        <button class="btn btn-edit btn-sm mb-1"
                                            onclick="openModal('modalEditJaringan{{ $jaringan->id_jaringan }}')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-delete btn-sm" onclick="confirmDelete({{ $jaringan->id_jaringan }})">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                        <form id="delete-form-{{ $jaringan->id_jaringan }}"
                                            action="{{ route('jaringan.destroy', $jaringan->id_jaringan) }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <div id="modalViewJaringan{{ $jaringan->id_jaringan }}" class="modal">
                            <div class="modal-content jaringan">
                                <span class="close"
                                    onclick="closeModal('modalViewJaringan{{ $jaringan->id_jaringan }}')">×</span>
                                <h5>Detail Jaringan</h5>
                                <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                                    <div style="display: flex; gap: 10px;">
                                        <div style="width: 32%;">
                                            <label>Region</label>
                                            <input type="text"
                                                value="{{ $jaringan->region ? $jaringan->region->nama_region : 'N/A' }}"
                                                readonly class="form-control">
                                            <label>Kode Site Insan</label>
                                            <input type="text" value="{{ $jaringan->kode_site_insan ?? 'N/A' }}" readonly
                                                class="form-control">
                                            <label>Panjang</label>
                                            <input type="text" value="{{ $jaringan->panjang ?? 'N/A' }}" readonly
                                                class="form-control">
                                            <label>Tipe Kabel</label>
                                            <input type="text" value="{{ $jaringan->tipe_kabel ?? 'N/A' }}" readonly
                                                class="form-control">
                                            <label>DCI EQX</label>
                                            <input type="text" value="{{ $jaringan->dci_eqx ?? 'N/A' }}" readonly
                                                class="form-control">
                                        </div>

                                        <div style="width: 32.5%;">
                                            <label>Tipe Jaringan</label>
                                            <input type="text"
                                                value="{{ $jaringan->tipejaringan ? $jaringan->tipejaringan->nama_tipejaringan : 'N/A' }}"
                                                readonly class="form-control">
                                            <label>Jartatup Jartaplok</label>
                                            <input type="text" value="{{ $jaringan->jartatup_jartaplok ?? 'N/A' }}" readonly
                                                class="form-control">
                                            <label>Panjang Drawing</label>
                                            <input type="text" value="{{ $jaringan->panjang_drawing ?? 'N/A' }}" readonly
                                                class="form-control">
                                            <label>Jenis Kabel</label>
                                            <input type="text" value="{{ $jaringan->jenis_kabel ?? 'N/A' }}" readonly
                                                class="form-control">
                                            <label>Status</label>
                                            <input type="text" value="{{ $jaringan->status ?? 'N/A' }}" readonly
                                                class="form-control">
                                        </div>

                                        <div style="width: 32%;">
                                            <label>Milik</label>
                                            <input type="text" value="{{ $jaringan->milik ?? 'N/A' }}" readonly
                                                class="form-control">
                                            <label>Segmen</label>
                                            <textarea readonly class="form-control"
                                                style="resize:none; max-height: 44.5px; padding: 5px; margin-bottom: 10.5px;">{{ $jaringan->segmen ?? 'N/A' }}</textarea>
                                            <label>Jumlah Core</label>
                                            <input type="text" value="{{ $jaringan->jumlah_core ?? 'N/A' }}" readonly
                                                class="form-control">
                                            <label>Keterangan</label>
                                            <input type="text" value="{{ $jaringan->keterangan ?? 'N/A' }}" readonly
                                                class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Edit Jaringan -->
                        <div id="modalEditJaringan{{ $jaringan->id_jaringan }}" class="modal">
                            <div class="modal-content jaringan">
                                <span class="close"
                                    onclick="closeModal('modalEditJaringan{{ $jaringan->id_jaringan }}')">×</span>
                                <h5>Edit Jaringan</h5>
                                <form action="{{ route('jaringan.update', $jaringan->id_jaringan) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                                        <div style="width: 32%;">
                                            <label>Kode Region</label>
                                            <select name="kode_region" class="form-control" required>
                                                <option value="">Pilih Region</option>
                                                @foreach ($regions as $region)
                                                    <option value="{{ $region->kode_region }}" {{ $jaringan->kode_region == $region->kode_region ? 'selected' : '' }}>
                                                        {{ $region->nama_region }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            <label>Kode Site Insan</label>
                                            <input type="text" name="kode_site_insan" class="form-control"
                                                value="{{ $jaringan->kode_site_insan ?? '' }}">

                                            <label>Panjang</label>
                                            <input type="text" name="panjang" class="form-control"
                                                value="{{ $jaringan->panjang ?? '' }}">

                                            <label>Tipe Kabel</label>
                                            <input type="text" name="tipe_kabel" class="form-control"
                                                value="{{ $jaringan->tipe_kabel ?? '' }}">

                                            <label>DCI EQX</label>
                                            <input type="text" name="dci_eqx" class="form-control"
                                                value="{{ $jaringan->dci_eqx ?? '' }}">
                                        </div>

                                        <div style="width: 32.5%;">
                                            <label>Kode Tipe Jaringan</label>
                                            <select name="kode_tipejaringan" class="form-control" required>
                                                <option value="">Pilih Tipe Jaringan</option>
                                                @foreach ($types as $type)
                                                    <option value="{{ $type->kode_tipejaringan }}" {{ $jaringan->kode_tipejaringan == $type->kode_tipejaringan ? 'selected' : '' }}>
                                                        {{ $type->nama_tipejaringan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <label>Jartatup Jartaplok</label>
                                            <input type="text" name="jartatup_jartaplok" class="form-control"
                                                value="{{ $jaringan->jartatup_jartaplok ?? '' }}">
                                            <label>Panjang Drawing</label>
                                            <input type="text" name="panjang_drawing" class="form-control"
                                                value="{{ $jaringan->panjang_drawing ?? '' }}">
                                            <label>Jenis Kabel</label>
                                            <input type="text" name="jenis_kabel" class="form-control"
                                                value="{{ $jaringan->jenis_kabel ?? '' }}">
                                            <label>Status</label>
                                            <input type="text" name="status" class="form-control"
                                                value="{{ $jaringan->status ?? '' }}">
                                        </div>

                                        <div style="width: 32%;">
                                            <label>Milik</label>
                                            <select name="milik" class="form-control" required>
                                                <option value="">Pilih Kepemilikan</option>
                                                @foreach ($users as $milik)
                                                    <option value="{{ $milik->id }}" {{ $jaringan->milik == $milik->id ? 'selected' : '' }}>
                                                        {{ $milik->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <label>Segmen</label>
                                            <input type="text" name="segmen" class="form-control"
                                                value="{{ $jaringan->segmen ?? '' }}">
                                            <label>Jumlah Core</label>
                                            <input type="text" name="jumlah_core" class="form-control"
                                                value="{{ $jaringan->jumlah_core ?? '' }}">
                                            <label>Keterangan</label>
                                            <input type="text" name="keterangan" class="form-control"
                                                value="{{ $jaringan->keterangan ?? '' }}">
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary mt-3">Simpan Perubahan</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Modal Tambah Jaringan -->
        <div id="modalTambahJaringan" class="modal">
            <div class="modal-content jaringan">
                <span class="close" onclick="closeModal('modalTambahJaringan')">×</span>
                <h5>Tambah Jaringan</h5>
                <form action="{{ route('jaringan.store') }}" method="POST">
                    @csrf
                    <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                        <div style="width: 32%;">
                            <label>Kode Region</label>
                            <select name="kode_region" class="form-control" required>
                                <option value="">Pilih Region</option>
                                @foreach ($regions as $region)
                                    <option value="{{ $region->kode_region }}">{{ $region->nama_region }}</option>
                                @endforeach
                            </select>

                            <label>Kode Site Insan</label>
                            <input type="text" name="kode_site_insan" class="form-control">

                            <label>Panjang</label>
                            <input type="text" name="panjang" class="form-control">

                            <label>Tipe Kabel</label>
                            <input type="text" name="tipe_kabel" class="form-control">

                            <label>DCI EQX</label>
                            <input type="text" name="dci_eqx" class="form-control">
                        </div>

                        <div style="width: 32%;">

                            <label>Kode Tipe Jaringan</label>
                            <select name="kode_tipejaringan" class="form-control" required>
                                <option value="">Pilih Tipe Jaringan</option>
                                @foreach ($types as $type)
                                    <option value="{{ $type->kode_tipejaringan }}">{{ $type->nama_tipejaringan }}</option>
                                @endforeach
                            </select>

                            <label>Jartatup Jartaplok</label>
                            <input type="text" name="jartatup_jartaplok" class="form-control">

                            <label>Panjang Drawing</label>
                            <input type="text" name="panjang_drawing" class="form-control">

                            <label>Jenis Kabel</label>
                            <input type="text" name="jenis_kabel" class="form-control">

                            <label>Status</label>
                            <input type="text" name="status" class="form-control">
                        </div>

                        <div style="width: 32%;">
                            <label>Milik</label>
                            <select name="milik" class="form-control" required>
                                <option value="">Pilih Kepemilikan</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>

                            <label>Segmen</label>
                            <input type="text" name="segmen" class="form-control" required>

                            <label>Jumlah Core</label>
                            <input type="text" name="jumlah_core" class="form-control">

                            <label>Keterangan</label>
                            <input type="text" name="keterangan" class="form-control">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Tambah</button>
                </form>
            </div>
        </div>

        <div id="importModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('importModal')">×</span>
                <h5>Impor Data Jaringan</h5>
                <form action="{{ route('import.jaringan') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="file">Pilih File (XLSX)</label>
                        <input type="file" class="form-control" name="file" accept=".xlsx,.xls,.csv" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Impor Data</button>
                </form>
            </div>
        </div>

        <!-- Modal Ekspor Data -->
        <div id="exportModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('exportModal')">×</span>
                <h5>Ekspor Data Jaringan</h5>
                <form id="exportForm" action="{{ url('export/jaringan') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="regions">Pilih Region:</label>
                        <div id="regions">
                            @foreach ($regions as $region)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="regions[]"
                                        value="{{ $region->kode_region }}" id="region-{{ $loop->index }}">
                                    <label class="form-check-label" for="region-{{ $loop->index }}">
                                        {{ $region->nama_region }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <div class="mb-3" style="margin-top: 20px;">
                            <label for="format">Pilih Format File:</label>
                            <select name="format" id="format" class="form-select" required>
                                <option value="excel">Excel (.xlsx)</option>
                                <option value="pdf">PDF (.pdf)</option>
                            </select>
                        </div>
                        <small class="text-muted">*Jika tidak memilih, semua data region akan diekspor.</small>
                    </div>
                    <button type="submit" class="btn btn-primary" style="margin-top: 15px;">Ekspor</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2();
            $('#jaringanTable').DataTable({
                language: {
                    search: "Cari",
                    lengthMenu: "_MENU_",
                    zeroRecords: "Tidak ada data yang ditemukan",
                    info: "Menampilkan halaman _PAGE_ dari _PAGES_",
                    infoEmpty: "Tidak ada data yang tersedia",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "<i class='fas fa-arrow-right'></i>",
                        previous: "<i class='fas fa-arrow-left'></i>"
                    }
                },
                pageLength: 10,
                lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Semua"]],
                order: [],
                columnDefs: [
                    { targets: [7], orderable: false }
                ]
            });
        });
    </script>
@endsection