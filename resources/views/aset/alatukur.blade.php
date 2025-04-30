@extends('layouts.app')

@section('title', 'Aset Alatukur')
@section('page_title', 'Aset Alatukur')

@section('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
    <div class="main">
        <div class="button-wrapper">
            <button class="btn btn-primary mb-3" onclick="openModal('modalTambahAlatukur')">+ Tambah Alatukur</button>
            <button type="button" class="btn btn-primary mb-3" onclick="openModal('importModal')">Impor Data
                Alatukur</button>
            <button type="button" class="btn btn-primary mb-3" onclick="openModal('exportModal')">Export Data
                Alatukur</button>
        </div>

        <div class="table-responsive">
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
                    @foreach($dataalatukur as $alatukur)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $alatukur->region->nama_region }}</td>
                            <td>{{ $alatukur->jenisalatukur->nama_alatukur }}</td>
                            <td>{{ optional($alatukur->brandalatukur)->nama_brand }}</td>
                            <td>{{ $alatukur->type }}</td>
                            <td>{{ $alatukur->serialnumber }}</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-eye btn-sm mb-1"
                                        onclick="openModal('modalViewAlatukur{{ $alatukur->id_alatukur }}')">
                                        <i class="fas fa-eye"></i> Lihat
                                    </button>
                                    <button class="btn btn-edit btn-sm mb-1"
                                        onclick="openModal('modalEditAlatukur{{ $alatukur->id_alatukur }}')">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-delete btn-sm"
                                        onclick="confirmDelete({{ $alatukur->id_alatukur }})">
                                        <i class="fas fa-trash-alt"></i> Hapus
                                    </button>

                                    <form id="delete-form-{{ $alatukur->id_alatukur }}"
                                        action="{{ route('alatukur.destroy', $alatukur->id_alatukur) }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <!-- Modal View -->
                        <div id="modalViewAlatukur{{ $alatukur->id_alatukur }}" class="modal">
                            <div class="modal-content">
                                <span class="close"
                                    onclick="closeModal('modalViewAlatukur{{ $alatukur->id_alatukur }}')">&times;</span>
                                <h5>Detail Alatukur</h5>

                                <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                                    <div style="width: 48%;">
                                        <label>Region</label>
                                        <input type="text" value="{{ $alatukur->region->nama_region }}" readonly
                                            class="form-control">
                                
                                        <label>Brand</label>
                                        <input type="text" value="{{ optional($alatukur->brandalatukur)->nama_brand }}"
                                            readonly class="form-control">

                                        <label>Alatukur ke-</label>
                                        <input type="text" value="{{ $alatukur->alatukur_ke }}" readonly class="form-control">

                                        <label>Tahun Perolehan</label>
                                        <input type="text" value="{{ $alatukur->tahunperolehan }}" readonly
                                            class="form-control">

                                        <label>Keterangan</label>
                                        <input type="text" value="{{ $alatukur->keterangan }}" readonly class="form-control">
                                    </div>

                                    <div style="width: 48%;">
                                    <label>Jenis Alatukur</label>
                                        <input type="text" value="{{ $alatukur->jenisalatukur->nama_alatukur }}" readonly
                                            class="form-control">


                                        <label>Tipe</label>
                                        <input type="text" value="{{ $alatukur->type }}" readonly class="form-control">

                                        <label>Serial Number</label>
                                        <input type="text" value="{{ $alatukur->serialnumber }}" readonly class="form-control">


                                        <label>Kondisi</label>
                                        <input type="text" value="{{ $alatukur->kondisi }}" readonly class="form-control">
                                    
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div id="modalEditAlatukur{{ $alatukur->id_alatukur }}" class="modal">
                            <div class="modal-content">
                                <span class="close"
                                    onclick="closeModal('modalEditAlatukur{{ $alatukur->id_alatukur }}')">&times;</span>
                                <h5>Edit Alatukur</h5>
                                <action="{{ route('alatukur.update', $alatukur->id_alatukur) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                                        <div style="width: 48%;">
                                            <label>Kode Region</label>
                                            <select name="kode_region" class="form-control regionSelectEdit"
                                                data-id="{{ $alatukur->id_alatukur }}" required>
                                                <option value="">Pilih Region</option>
                                                @foreach($regions as $region)
                                                    <option value="{{ $region->kode_region }}" {{ $alatukur->kode_region == $region->kode_region ? 'selected' : '' }}>
                                                        {{ $region->nama_region }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            <label>Kode Brand</label>
                                            <select name="kode_brand" class="form-control">
                                                <option value="">Pilih Kode Brand</option>
                                                @foreach($brands as $brandalatukur)
                                                    <option value="{{ $brandalatukur->kode_brand }}" {{ $alatukur->kode_brand == $brandalatukur->kode_brand ? 'selected' : '' }}>
                                                        {{ $brandalatukur->nama_brand }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            <label>Serial Number</label>
                                            <input type="text" name="serialnumber" class="form-control"
                                                value="{{ $alatukur->serialnumber ?? '' }}">

                                            <label>Kondisi</label>
                                            <input type="text" name="kondisi" class="form-control"
                                                value="{{ $alatukur->kondisi ?? '' }}">
                                            
                                        </div>

                                        <div style="width: 48%;">
                                            <label>Kode Alatukur</label>
                                            <select name="kode_alatukur" class="form-control" required>
                                                <option value="">Pilih Kode Alatukur</option>
                                                @foreach($types as $jenisalatukur)
                                                    <option value="{{ $jenisalatukur->kode_alatukur }}" {{ $alatukur->kode_alatukur == $jenisalatukur->kode_alatukur ? 'selected' : '' }}>
                                                        {{ $jenisalatukur->nama_alatukur }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            <label>Type</label>
                                            <input type="text" name="type" class="form-control"
                                                value="{{ $alatukur->type ?? '' }}">
                                    
                                            <label>Tahun Perolehan</label>
                                            <input type="text" name="tahunperolehan" class="form-control"
                                                value="{{ $alatukur->tahunperolehan ?? '' }}">

                                            <label>Keterangan</label>
                                            <input type="text" name="keterangan" class="form-control"
                                                value="{{ $alatukur->keterangan ?? '' }}">
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                    </form>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div id="importModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('importModal')">&times;</span>
                <h5>Impor Data Alatukur</h5>
                <form action="{{ route('import.alatukur') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="file">Pilih File (XLSX, XLS, CSV)</label>
                        <input type="file" class="form-control" name="file" accept=".xlsx,.xls,.csv" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Impor Data</button>
                </form>
            </div>
        </div>

        <div id="exportModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('exportModal')">&times;</span>
                <h5>Ekspor Data Alatukur</h5>
                <form id="exportForm" action="{{ url('export/alatukur') }}" method="POST">
                @csrf
                    <div class="mb-3">
                        <label for="regions">Pilih Region:</label>
                        <div id="regions">
                            @foreach ($regions as $region)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="regions[]"
                                        value="{{ $region['kode_region'] }}" id="region-{{ $loop->index }}">
                                    <a class="form-check-label" for="region-{{ $loop->index }}">
                                        {{ $region['nama_region'] }}
                                    </a>
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
                <span class="close" onclick="closeModal('modalTambahAlatukur')">&times;</span>
                <h5>Tambah Alatukur</h5>
                <form action="{{ route('alatukur.store') }}" method="POST" id="formTambahAlatukur">
                    @csrf
                    <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                        <div style="width: 48%;">
                            <label>Kode Region</label>
                            <select id="regionSelectTambah" name="kode_region" class="form-control" required>
                                <option value="">Pilih Region</option>
                                @foreach($regions as $region)
                                    <option value="{{ $region->kode_region }}">{{ $region->nama_region }}</option>
                                @endforeach
                            </select>

                            <label>Kode Brand</label>
                            <select name="kode_brand" class="form-control">
                                <option value="">Pilih Kode Brand</option>
                                @foreach($brands as $brandalatukur)
                                    <option value="{{ $brandalatukur->kode_brand }}">
                                        {{ $brandalatukur->nama_brand }}
                                    </option>
                                @endforeach
                            </select>
                            
                            <label>Serial Number</label>
                            <input type="text" name="serialnumber" class="form-control" value="">

                            <label>Kondisi</label>
                            <input type="text" name="kondisi" class="form-control" value="">
                            
                        </div>

                        <div style="width: 48%;">
                            <label>Kode Alatukur</label>
                            <select name="kode_alatukur" class="form-control" required>
                                <option value="">Pilih Kode Alatukur</option>
                                @foreach($types as $jenisalatukur)
                                    <option value="{{ $jenisalatukur->kode_alatukur }}">
                                        {{ $jenisalatukur->nama_alatukur }}
                                    </option>
                                @endforeach
                            </select>
                        
                            <label>Type</label>
                            <input type="text" name="type" class="form-control" value="">

                            <label>Tahun Perolehan</label>
                            <input type="text" name="tahunperolehan" class="form-control" value="">

                            <label>Keterangan</label>
                            <input type="text" name="keterangan" class="form-control" value="">
                        </div>
                    </div>                       
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </form>
            </div>
        </div>

    </div>

    @section('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
        <script>
            $(document).ready(function () {
                $('#alatukurTable').DataTable({
                    "language": {
                        "search": "Cari",
                        "lengthMenu": "_MENU_",
                        "zeroRecords": "Tidak ada data yang ditemukan",
                        "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                        "infoEmpty": "Tidak ada data yang tersedia",
                        "infoFiltered": "(difilter dari _MAX_ total data)",
                        "paginate": {
                            "first": "Pertama",
                            "last": "Terakhir",
                            "next": "<i class='fas fa-arrow-right'></i>",
                            "previous": "<i class='fas fa-arrow-left'></i>"
                        }
                    },
                    "pageLength": 10,
                    "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Semua"]],
                    "columnDefs": [
                        {
                            "targets": [0, 8],
                            "orderable": false
                        }
                    ]
                });
            });


        document.getElementById('exportForm').addEventListener('submit', function (e) {
                e.preventDefault(); // Biar gak reload halaman

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
                    return response.blob(); // Misalnya kamu kirim file
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

                    // Kalau ingin langsung download file:
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    const format = formData.get('format');
                    a.download = `dataalatukur.${format === 'pdf' ? 'pdf' : 'xlsx'}`;
                    a.click();
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops!',
                        text: error.message
                    });
                });
            });

            function closeModal(id) {
                const modal = document.getElementById(id);
                if (modal) {
                    modal.style.display = "none";
                }
            }
            
        </script>
    @endsection
@endsection