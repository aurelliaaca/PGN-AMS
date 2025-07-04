@extends('layouts.app')

@section('title', 'Aset Alatukur')
@section('page_title', 'Aset Alatukur')

@section('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
    <div class="main">
        @if (auth()->user()->role == '1' || auth()->user()->role == '2')
            <div class="button-wrapper" style="margin-top: 20px;">
                <button class="btn btn-primary mb-3" onclick="openModal('modalTambahAlatukur')">+ Tambah Alatukur</button>
                <button type="button" class="btn btn-primary mb-3" onclick="openModal('importModal')">Impor Data Alatukur</button>
                <button type="button" class="btn btn-primary mb-3" onclick="openModal('exportModal')">Ekspor Data Alatukur</button>
            </div>

            <form method="GET" action="{{ route('alatukur.index') }}" id="filterForm">
                <div class="filter-container" style="margin-top: 20px;">
                    <select name="kode_region[]" class="select2" multiple data-placeholder="Pilih Region" onchange="document.getElementById('filterForm').submit()">
                        @foreach ($regions as $region)
                            <option value="{{ $region->kode_region }}" {{ in_array($region->kode_region, request('kode_region', [])) ? 'selected' : '' }}>
                                {{ $region->nama_region }}
                            </option>
                        @endforeach
                    </select>

                    <select name="kode_alatukur[]" class="select2" multiple data-placeholder="Pilih Alatukur" onchange="document.getElementById('filterForm').submit()">
                        @foreach ($types as $type)
                            <option value="{{ $type->kode_alatukur }}" {{ in_array($type->kode_alatukur, request('kode_alatukur', [])) ? 'selected' : '' }}>
                                {{ $type->nama_alatukur }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        @endif

        <div class="table-responsive" style="margin-top: 20px;">
            <table id="alatukurTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Region</th>
                        <th>Alatukur</th>
                        <th>Brand</th>
                        <th>Type</th>
                        <th>Serial Number</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dataalatukur as $alatukur)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $alatukur->region->nama_region }}</td>
                            <td>{{ $alatukur->jenisalatukur->nama_alatukur }}</td>
                            <td>{{ optional($alatukur->brandalatukur)->nama_brand }}</td>
                            <td>{{ $alatukur->type }}</td>
                            <td>{{ $alatukur->serialnumber }}</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-eye btn-sm mb-1" onclick="openModal('modalViewAlatukur{{ $alatukur->id_alatukur }}')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if (auth()->user()->role == '1' || auth()->user()->role == '2')
                                        <button class="btn btn-edit btn-sm mb-1" onclick="openModal('modalEditAlatukur{{ $alatukur->id_alatukur }}')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-delete btn-sm" onclick="confirmDelete({{ $alatukur->id_alatukur }})">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                        <form id="delete-form-{{ $alatukur->id_alatukur }}" action="{{ route('alatukur.destroy', $alatukur->id_alatukur) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <div id="modalViewAlatukur{{ $alatukur->id_alatukur }}" class="modal">
                            <div class="modal-content">
                                <span class="close" onclick="closeModal('modalViewAlatukur{{ $alatukur->id_alatukur }}')">×</span>
                                <h5>Detail Alatukur</h5>
                                <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                                    <div style="width: 48%;">
                                        <label>Region</label>
                                        <input type="text" value="{{ $alatukur->region->nama_region }}" readonly class="form-control">
                                        <label>Jenis</label>
                                        <input type="text" value="{{ $alatukur->jenisalatukur->nama_alatukur }}" readonly class="form-control">
                                        <label>Type</label>
                                        <input type="text" value="{{ $alatukur->type }}" readonly class="form-control">
                                        <label>Kondisi</label>
                                        <input type="text" value="{{ $alatukur->kondisi }}" readonly class="form-control">                                 
                                        <label>Keterangan</label>
                                        <input type="text" value="{{ $alatukur->keterangan }}" readonly class="form-control">
                                    </div>
                                    <div style="width: 48%;">
                                        <label>Milik</label>
                                        <input type="text" value="{{ optional($alatukur->user)->name }}" readonly class="form-control">
                                        <label>Brand</label>
                                        <input type="text" value="{{ optional($alatukur->brandalatukur)->nama_brand }}" readonly class="form-control">
                                        <label>Serial Number</label>
                                        <input type="text" value="{{ $alatukur->serialnumber }}" readonly class="form-control">
                                        <label>Tahun Perolehan</label>
                                        <input type="text" value="{{ $alatukur->tahunperolehan }}" readonly class="form-control">
                                        <label>Alatukur ke-</label>
                                        <input type="text" value="{{ $alatukur->alatukur_ke }}" readonly class="form-control">   
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="modalEditAlatukur{{ $alatukur->id_alatukur }}" class="modal">
                            <div class="modal-content">
                                <span class="close" onclick="closeModal('modalEditAlatukur{{ $alatukur->id_alatukur }}')">×</span>
                                <h5>Edit Alatukur</h5>
                                <form action="{{ route('alatukur.update', $alatukur->id_alatukur) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                                        <div style="width: 48%;">
                                            <label>Region</label>
                                            <select name="kode_region" class="form-control regionSelectEdit" data-id="{{ $alatukur->id_alatukur }}" required>
                                                <option value="">Pilih Region</option>
                                                @foreach ($regions as $region)
                                                    <option value="{{ $region->kode_region }}" {{ $alatukur->kode_region == $region->kode_region ? 'selected' : '' }}>
                                                        {{ $region->nama_region }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <label>Jenis</label>
                                            <select name="kode_alatukur" class="form-control" required>
                                                <option value="">Pilih Alatukur</option>
                                                @foreach ($types as $jenisalatukur)
                                                    <option value="{{ $jenisalatukur->kode_alatukur }}" {{ $alatukur->kode_alatukur == $jenisalatukur->kode_alatukur ? 'selected' : '' }}>
                                                        {{ $jenisalatukur->nama_alatukur }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <label>Type</label>
                                            <input type="text" name="type" class="form-control" value="{{ $alatukur->type ?? '' }}">
                                            <label>Kondisi</label>
                                            <input type="text" name="kondisi" class="form-control" value="{{ $alatukur->kondisi ?? '' }}">
                                            <label>Keterangan</label>
                                            <input type="text" name="keterangan" class="form-control" value="{{ $alatukur->keterangan ?? '' }}">
                                        </div>
                                        <div style="width: 48%;">
                                            <label>Milik</label>
                                            <select name="milik" class="form-control" required>
                                                <option value="">Pilih Kepemilikan</option>
                                                @foreach ($users as $milik)
                                                    <option value="{{ $milik->id }}" {{ $alatukur->milik == $milik->id ? 'selected' : '' }}>
                                                        {{ $milik->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <label>Brand</label>
                                            <select name="kode_brand" class="form-control">
                                                <option value="">Pilih Brand</option>
                                                @foreach ($brands as $brandalatukur)
                                                    <option value="{{ $brandalatukur->kode_brand }}" {{ $alatukur->kode_brand == $brandalatukur->kode_brand ? 'selected' : '' }}>
                                                        {{ $brandalatukur->nama_brand }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <label>Serial Number</label>
                                            <input type="text" name="serialnumber" class="form-control" value="{{ $alatukur->serialnumber ?? '' }}">
                                            <label>Tahun Perolehan</label>
                                            <input type="text" name="tahunperolehan" class="form-control" value="{{ $alatukur->tahunperolehan ?? '' }}">
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

        <div id="importModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('importModal')">×</span>
                <h5>Impor Data Alatukur</h5>
                <form action="{{ route('import.alatukur') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="file">Pilih File (XLSX)</label>
                        <input type="file" class="form-control" name="file" accept=".xlsx,.xls,.csv" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Impor Data</button>
                </form>
            </div>
        </div>

        <div id="exportModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('exportModal')">×</span>
                <h5>Ekspor Data Alatukur</h5>
                <form id="exportForm" action="{{ url('export/alatukur') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="regions">Pilih Region:</label>
                        <div id="regions">
                            @foreach ($regions as $region)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="regions[]" value="{{ $region->kode_region }}" id="region-{{ $loop->index }}">
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

        <div id="modalTambahAlatukur" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('modalTambahAlatukur')">×</span>
                <h5>Tambah Alatukur</h5>
                <form action="{{ route('alatukur.store') }}" method="POST" id="formTambahAlatukur">
                    @csrf
                    <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                        <div style="width: 48%;">
                            <label>Region</label>
                            <select id="regionSelectTambah" name="kode_region" class="form-control" required>
                                <option value="">Pilih Region</option>
                                @foreach ($regions as $region)
                                    <option value="{{ $region->kode_region }}">{{ $region->nama_region }}</option>
                                @endforeach
                            </select>
                            <label>Jenis</label>
                            <select name="kode_alatukur" class="form-control" required>
                                <option value="">Pilih Alatukur</option>
                                @foreach ($types as $jenisalatukur)
                                    <option value="{{ $jenisalatukur->kode_alatukur }}">{{ $jenisalatukur->nama_alatukur }}</option>
                                @endforeach
                            </select>
                            <label>Type</label>
                            <input type="text" name="type" class="form-control" value="">
                            <label>Kondisi</label>
                            <input type="text" name="kondisi" class="form-control" value="">
                            <label>Keterangan</label>
                            <input type="text" name="keterangan" class="form-control" value="">
                        </div>
                        <div style="width: 48%;">
                            <label>Milik</label>
                            <select name="milik" class="form-control" required>
                                <option value="">Pilih Kepemilikan</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            <label>Brand</label>
                            <select name="kode_brand" class="form-control">
                                <option value="">Pilih Brand</option>
                                @foreach ($brands as $brandalatukur)
                                    <option value="{{ $brandalatukur->kode_brand }}">{{ $brandalatukur->nama_brand }}</option>
                                @endforeach
                            </select>
                            <label>Serial Number</label>
                            <input type="text" name="serialnumber" class="form-control" value="">
                            <label>Tahun Perolehan</label>
                            <input type="text" name="tahunperolehan" class="form-control" value="">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Tambah</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
   <script>
        $(document).ready(function () {
            // Initialize Select2
            $('.select2').select2({
                width: '100%',
                placeholder: function () {
                    return $(this).data('placeholder');
                },
                allowClear: true
            });

            $('select[name="kode_region[]"], select[name="kode_alatukur[]"]').on('change', function () {
                $('#filterForm').submit();
            });

            $('select[name="kode_fasilitas[]"]').on('change', function() {
                    $('#filterForm').submit();
                });

            $('#alatukurTable').DataTable({
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
                columnDefs: [
                    { targets: [6], orderable: false }
                ]
            });

            document.getElementById('exportForm').addEventListener('submit', function (e) {
                e.preventDefault();
                const form = e.target;
                const formData = new FormData(form);

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Gagal ekspor data!');
                        }
                        return response.blob();
                    })
                    .then(blob => {
                        closeModal('exportModal');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Data berhasil diekspor!',
                            timer: 2000,
                            showConfirmButton: false
                        });

                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        const format = formData.get('format');
                        a.download = `dataalatukur.${format === 'pdf' ? 'pdf' : 'xlsx'}`;
                        a.click();
                        window.URL.revokeObjectURL(url);
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops!',
                            text: error.message
                        });
                    });
            });

            document.getElementById('formTambahAlatukur').addEventListener('submit', function (event) {
                const tahunPerolehan = this.querySelector('input[name="tahunperolehan"]').value;
                const serialNumber = this.querySelector('input[name="serialnumber"]').value;

                if (tahunPerolehan && !/^\d{4}$/.test(tahunPerolehan)) {
                    alert('Tahun Perolehan harus berupa 4 digit angka.');
                    event.preventDefault();
                    return;
                }

                if (serialNumber && serialNumber.length > 50) {
                    alert('Serial Number tidak boleh lebih dari 50 karakter.');
                    event.preventDefault();
                    return;
                }
            });

            document.querySelectorAll('form[action*="alatukur/update"]').forEach(form => {
                form.addEventListener('submit', function (event) {
                    const tahunPerolehan = this.querySelector('input[name="tahunperolehan"]').value;
                    const serialNumber = this.querySelector('input[name="serialnumber"]').value;

                    if (tahunPerolehan && !/^\d{4}$/.test(tahunPerolehan)) {
                        alert('Tahun Perolehan harus berupa 4 digit angka.');
                        event.preventDefault();
                        return;
                    }

                    if (serialNumber && serialNumber.length > 50) {
                        alert('Serial Number tidak boleh lebih dari 50 karakter.');
                        event.preventDefault();
                        return;
                    }
                });
            });

            function closeModal(id) {
                const modal = document.getElementById(id);
                if (modal) {
                    modal.style.display = 'none';
                }
            }
        });
    </script>
@endsection
