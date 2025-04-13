@extends('layouts.app')

@section('title', 'Aset Perangkat')
@section('page_title', 'Aset Perangkat')

@section('content')
    <div class="main">
        <button class="btn btn-primary mb-3" onclick="openModal('modalTambahPerangkat')">+ Tambah Perangkat</button>
        <button type="button" class="btn btn-primary" onclick="openModal('importModal')">Impor Data Perangkat</button>

        <div id="importModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('importModal')">&times;</span>
                <h5>Impor Data Perangkat</h5>
                <form action="{{ route('import.perangkat') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="file">Pilih File (XLSX, XLS, CSV)</label>
                    <input type="file" class="form-control" name="file" accept=".xlsx,.xls,.csv" required>
                </div>
                <button type="submit" class="btn btn-primary">Impor Data</button>
                </form>
            </div>
        </div>

        <button class="btn btn-primary mb-3">
            <a href="{{ url('export/perangkat') }}" style="color: white; text-decoration: none;">
                Ekspor Data Perangkat
            </a>
        </button>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                    <th class="col-status"></th>
                    <th class="col-no">No</th>
                    <th class="col-region">Region</th>
                    <th class="col-site">Site</th>
                    <th class="col-rack">No Rack</th>
                    <th class="col-nama">Perangkat</th>
                    <th class="col-brand">Brand</th>
                    <th class="col-type">Type</th>
                    <th class="col-actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($listperangkat as $perangkat)
                        <tr>
                            <td>
                                <div class="status-box {{ $perangkat->no_rack ? 'bg-success' : 'bg-danger' }}"></div>
                            </td>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $perangkat->region->nama_region }}</td>
                            <td>{{ $perangkat->site->nama_site }}</td>
                            <td>{{ $perangkat->no_rack }}</td>
                            <td>{{ $perangkat->jenisperangkat->nama_perangkat }}</td>
                            <td>{{ optional($perangkat->brandperangkat)->nama_brand }}</td>
                            <td>{{ $perangkat->type }}</td>
                            <td>
                                <button class="btn btn-eye"
                                    onclick="openModal('modalViewPerangkat{{ $perangkat->id_perangkat }}')">
                                    <i class="fas fa-eye"></i>
                                </button>   
                                <button class="btn btn-edit"
                                    onclick="openModal('modalEditPerangkat{{ $perangkat->id_perangkat }}')">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-delete" onclick="confirmDelete({{ $perangkat->id_perangkat }})">
                                    <i class="fas fa-trash-alt"></i>
                                </button>

                                <form id="delete-form-{{ $perangkat->id_perangkat }}" 
                                    action="{{ route('perangkat.destroy', $perangkat->id_perangkat) }}" 
                                    method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>

                        <!-- Modal View -->
                        <div id="modalViewPerangkat{{ $perangkat->id_perangkat }}" class="modal">
                            <div class="modal-content">
                                <span class="close" onclick="closeModal('modalViewPerangkat{{ $perangkat->id_perangkat }}')">&times;</span>
                                <h5>Detail Perangkat</h5>
                                
                                <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                                    <!-- Kolom kiri -->
                                    <div style="width: 48%;">
                                        <label>Region</label>
                                        <input type="text" value="{{ $perangkat->region->nama_region }}" readonly class="form-control">

                                        <label>Site</label>
                                        <input type="text" value="{{ $perangkat->site->nama_site }}" readonly class="form-control">

                                        <label>No Rack</label>
                                        <input type="text" value="{{ $perangkat->no_rack }}" readonly class="form-control">

                                        <label>Jenis Perangkat</label>
                                        <input type="text" value="{{ $perangkat->jenisperangkat->nama_perangkat }}" readonly class="form-control">
                                    </div>

                                    <!-- Kolom kanan -->
                                    <div style="width: 48%;">
                                        <label>Perangkat ke-</label>
                                        <input type="text" value="{{ $perangkat->perangkat_ke }}" readonly class="form-control">

                                        <label>Brand</label>
                                        <input type="text" value="{{ optional($perangkat->brandperangkat)->nama_brand }}" readonly class="form-control">

                                        <label>Tipe</label>
                                        <input type="text" value="{{ $perangkat->type }}" readonly class="form-control">

                                        <label>U Awal - U Akhir</label>
                                        <input type="text" value="{{ $perangkat->uawal }} - {{ $perangkat->uakhir }}" readonly class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>


                        {{-- Modal Edit --}}
                        <div id="modalEditPerangkat{{ $perangkat->id_perangkat }}" class="modal">
                            <div class="modal-content">
                                <span class="close"
                                    onclick="closeModal('modalEditPerangkat{{ $perangkat->id_perangkat }}')">&times;</span>
                                <h5>Edit Perangkat</h5>
                                <form action="{{ route('perangkat.update', $perangkat->id_perangkat) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label>Kode Region</label>
                                        <select name="kode_region" class="form-control regionSelectEdit"
                                            data-id="{{ $perangkat->id_perangkat }}" required>
                                            <option value="">Pilih Region</option>
                                            @foreach($regions as $region)
                                                <option value="{{ $region->kode_region }}" {{ $perangkat->kode_region == $region->kode_region ? 'selected' : '' }}>
                                                    {{ $region->nama_region }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label>Kode Site</label>
                                        <select name="kode_site" class="form-control siteSelectEdit"
                                            data-id="{{ $perangkat->id_perangkat }}" required>
                                            <option value="">Pilih Site</option>
                                            @foreach($sites as $site)
                                                @if($site->kode_region == $perangkat->kode_region)
                                                    <option value="{{ $site->kode_site }}" {{ $perangkat->kode_site == $site->kode_site ? 'selected' : '' }}>
                                                        {{ $site->nama_site }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label>No Rack</label>
                                        <input type="text" name="no_rack" class="form-control"
                                            value="{{ $perangkat->no_rack ?? '' }}">
                                    </div>
                                    <div class="mb-3">
                                        <label>Kode Perangkat</label>
                                        <select name="kode_perangkat" class="form-control" required>
                                            <option value="">Pilih Kode Perangkat</option>
                                            @foreach($types as $jenisperangkat)
                                                <option value="{{ $jenisperangkat->kode_perangkat }}" 
                                                    {{ $perangkat->kode_perangkat == $jenisperangkat->kode_perangkat ? 'selected' : '' }}>{{ $jenisperangkat->nama_perangkat }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>                   
                                    <div class="mb-3">
                                        <label>Kode Brand</label>
                                        <select name="kode_brand" class="form-control">
                                            <option value="">Pilih Kode Brand</option>
                                            @foreach($brands as $brandperangkat)
                                                <option value="{{ $brandperangkat->kode_brand }}" 
                                                    {{ $perangkat->kode_brand == $brandperangkat->kode_brand ? 'selected' : '' }}>
                                                    {{ $brandperangkat->nama_brand }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label>Type</label>
                                        <input type="text" name="type" class="form-control" value="{{ $perangkat->type ?? '' }}"
                                            >
                                    </div>
                                    <div class="mb-3">
                                        <label>U Awal</label>
                                        <input type="number" name="uawal" class="form-control"
                                            value="{{ $perangkat->uawal ?? '' }}" >
                                    </div>
                                    <div class="mb-3">
                                        <label>U Akhir</label>
                                        <input type="number" name="uakhir" class="form-control"
                                            value="{{ $perangkat->uakhir ?? '' }}" >
                                    </div>
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Modal Tambah --}}
        <div id="modalTambahPerangkat" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('modalTambahPerangkat')">&times;</span>
                <h5>Tambah Perangkat</h5>
                <form action="{{ route('perangkat.store') }}" method="POST" id="formTambahPerangkat">
                    @csrf
                    <div class="mb-3">
            <label>Kode Region</label>
            <select id="regionSelectTambah" name="kode_region" class="form-control" required>
                <option value="">Pilih Region</option>
                @foreach($regions as $region)
                    <option value="{{ $region->kode_region }}">{{ $region->nama_region }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Kode Site</label>
            <select id="siteSelectTambah" name="kode_site" class="form-control" required disabled>
                <option value="">Pilih Site</option>
            </select>
        </div>

            <div class="mb-3">
                <label>No Rack</label>
                <input type="text" name="no_rack" class="form-control" id="no_rack" value="">
            </div>

            <div class="mb-3">
                <label>Kode Perangkat</label>
                <select name="kode_perangkat" class="form-control" required>
                    <option value="">Pilih Kode Perangkat</option>
                    @foreach($types as $jenisperangkat)
                        <option value="{{ $jenisperangkat->kode_perangkat }}">
                            {{ $jenisperangkat->nama_perangkat }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Kode Brand</label>
                <select name="kode_brand" class="form-control" >
                    <option value="">Pilih Kode Brand</option>
                    @foreach($brands as $brandperangkat)
                        <option value="{{ $brandperangkat->kode_brand }}">
                            {{ $brandperangkat->nama_brand }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Type</label>
                <input type="text" name="type" class="form-control" value="">
            </div>

            <div class="mb-3">
                <label>U Awal</label>
                <input type="number" name="uawal" class="form-control" value="" id="uawal">
            </div>

            <div class="mb-3">
                <label>U Akhir</label>
                <input type="number" name="uakhir" class="form-control" value="" id="uakhir">
            </div>

            <button type="submit" class="btn btn-primary">Tambah</button>
        </form>
    </div>
</div>


    </div>
    <script>
        // Menangani perubahan pada Region
        document.getElementById('regionSelectTambah').addEventListener('change', function() {
            const regionId = this.value;
            const siteSelect = document.getElementById('siteSelectTambah');

            // Reset dan nonaktifkan site select
            siteSelect.innerHTML = '<option value="">Pilih Site</option>';
            siteSelect.disabled = true;

            // Jika Region dipilih, aktifkan site select dan filter site berdasarkan region
            if (regionId) {
                siteSelect.disabled = false;
                const sites = @json($sites);
                const filteredSites = sites.filter(site => site.kode_region == regionId);

                filteredSites.forEach(site => {
                    const option = document.createElement('option');
                    option.value = site.kode_site;
                    option.textContent = site.nama_site;
                    siteSelect.appendChild(option);
                });
            }
        });

        // Menangani perubahan pada input no_rack
        document.getElementById('no_rack').addEventListener('input', function () {
            const noRack = this.value;
            const uawalField = document.getElementById('uawal');
            const uakhirField = document.getElementById('uakhir');

            // Jika no_rack diisi, uawal dan uakhir menjadi required
            if (noRack) {
                uawalField.setAttribute('required', 'required');
                uakhirField.setAttribute('required', 'required');
            } else {
                uawalField.removeAttribute('required');
                uakhirField.removeAttribute('required');
            }
        });

        // Validasi form sebelum submit
        document.getElementById('formTambahPerangkat').addEventListener('submit', function (event) {
            const uawal = parseFloat(document.getElementById('uawal').value);
            const uakhir = parseFloat(document.getElementById('uakhir').value);

            // Pastikan uawal < uakhir dan tidak bernilai negatif
            if (uawal >= uakhir) {
                alert('U Awal harus lebih kecil dari U Akhir.');
                event.preventDefault(); // Cegah form untuk dikirim
            }

            if (uawal < 0 || uakhir < 0) {
                alert('U Awal dan U Akhir tidak boleh bernilai negatif.');
                event.preventDefault(); // Cegah form untuk dikirim
            }
        });

        // Menangani perubahan pada Region di form edit
        document.querySelectorAll('.regionSelectEdit').forEach(select => {
            select.addEventListener('change', function() {
                const regionId = this.value;
                const perangkatId = this.getAttribute('data-id');
                const siteSelect = document.querySelector(`.siteSelectEdit[data-id="${perangkatId}"]`);

                // Reset dan nonaktifkan site select
                siteSelect.innerHTML = '<option value="">Pilih Site</option>';
                siteSelect.disabled = true;

                // Jika Region dipilih, aktifkan site select dan filter site berdasarkan region
                if (regionId) {
                    siteSelect.disabled = false;
                    const sites = @json($sites);
                    const filteredSites = sites.filter(site => site.kode_region == regionId);

                    filteredSites.forEach(site => {
                        const option = document.createElement('option');
                        option.value = site.kode_site;
                        option.textContent = site.nama_site;
                        siteSelect.appendChild(option);
                    });
                }
            });
        });

        // Validasi form edit sebelum submit
        document.querySelectorAll('form[action*="perangkat/update"]').forEach(form => {
            form.addEventListener('submit', function(event) {
                const uawal = parseFloat(this.querySelector('input[name="uawal"]').value);
                const uakhir = parseFloat(this.querySelector('input[name="uakhir"]').value);
                const noRack = this.querySelector('input[name="no_rack"]').value;

                // Jika no_rack diisi, pastikan uawal dan uakhir juga diisi
                if (noRack && (!uawal || !uakhir)) {
                    alert('U Awal dan U Akhir wajib diisi jika No Rack diisi.');
                    event.preventDefault();
                    return;
                }

                // Pastikan uawal < uakhir dan tidak bernilai negatif
                if (uawal >= uakhir) {
                    alert('U Awal harus lebih kecil dari U Akhir.');
                    event.preventDefault();
                }

                if (uawal < 0 || uakhir < 0) {
                    alert('U Awal dan U Akhir tidak boleh bernilai negatif.');
                    event.preventDefault();
                }
            });
        });
    </script>
@endsection
