@extends('layouts.app')

@section('title', 'Aset Fasilitas')
@section('page_title', 'Aset Fasilitas')

@section('content')
    <div class="main">
        <div class="button-wrapper">
            <button class="btn btn-primary mb-3" onclick="openModal('modalTambahFasilitas')">+ Tambah Fasilitas</button>
            <button type="button" class="btn btn-primary" onclick="openModal('importModal')">Impor Data Fasilitas</button>

            <div id="importModal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal('importModal')">&times;</span>
                    <h5>Impor Data Fasilitas</h5>
                    <form action="{{ route('import.fasilitas') }}" method="POST" enctype="multipart/form-data">
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
                <a href="{{ url('export/fasilitas') }}" style="color: white; text-decoration: none;">
                    Ekspor Data Fasilitas
                </a>
            </button>

            <button class="btn btn-clear mb-3" onclick="clearAllFilters()">Clear All Filter</button>
        </div>

        <div class="filter">
            <form method="GET" id="filterForm" action="{{ route('fasilitas.index') }}">
                <div class="filter-container">
                    <div class="select-group">
                        <select name="region[]" class="select2" multiple onchange="document.getElementById('filterForm').submit()">
                            <option value="" disabled>Pilih Region</option>
                            @foreach($regions as $region)
                                <option value="{{ $region->kode_region }}" {{ in_array($region->kode_region, (array) request('region')) ? 'selected' : '' }}>
                                    {{ $region->nama_region }}
                                </option>
                            @endforeach
                        </select>

                        <select name="site[]" class="select2" multiple onchange="document.getElementById('filterForm').submit()">
                            <option value="" disabled>Pilih Site</option>
                            @foreach ($sites as $site)
                                <option value="{{ $site->kode_site }}" {{ in_array($site->kode_site, (array) request('site')) ? 'selected' : '' }}>
                                    {{ $site->nama_site }}
                                </option>
                            @endforeach
                        </select>

                        <select name="kode_fasilitas[]" class="select2" multiple onchange="document.getElementById('filterForm').submit()">
                            <option value="" disabled>Pilih Fasilitas</option>
                            @foreach ($types as $kode)
                                <option value="{{ $kode->kode_fasilitas }}" {{ in_array($kode->kode_fasilitas, (array) request('kode_fasilitas')) ? 'selected' : '' }}>
                                    {{ $kode->nama_fasilitas }}
                                </option>
                            @endforeach
                        </select>

                        <select name="brand[]" class="select2" multiple onchange="document.getElementById('filterForm').submit()">
                            <option value="" disabled>Pilih Brand</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->kode_brand }}" {{ in_array($brand->kode_brand, (array) request('brand')) ? 'selected' : '' }}>
                                    {{ $brand->nama_brand }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="search-bar">
                        <form action="" method="GET" id="filterForm" style="display: flex; align-items: center; gap: 8px;">
                            <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" id="searchInput">
                            <button type="submit" class="btn btn-search">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive {{ Route::currentRouteName() == 'fasilitas.index' ? 'table-responsive-aset' : '' }}">
            <table class="table">
                <thead>
                    <tr>
                    <th class="col-status"></th>
                    <th class="col-no">No</th>
                    <th class="col-region">Region</th>
                    <th class="col-site">Site</th>
                    <th class="col-rack">No Rack</th>
                    <th class="col-nama">Fasilitas</th>
                    <th class="col-brand">Brand</th>
                    <th class="col-type">Type</th>
                    <th class="col-actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($datafasilitas as $fasilitas)
                        <tr>
                            <td>
                                <div class="status-box {{ $fasilitas->no_rack ? 'bg-success' : 'bg-danger' }}"></div>
                            </td>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $fasilitas->region->nama_region }}</td>
                            <td>{{ $fasilitas->site->nama_site }}</td>
                            <td>{{ $fasilitas->no_rack }}</td>
                            <td>{{ $fasilitas->jenisfasilitas->nama_fasilitas }}</td>
                            <td>{{ optional($fasilitas->brandfasilitas)->nama_brand }}</td>
                            <td>{{ $fasilitas->type }}</td>
                            <td>
                                <button class="btn btn-eye"
                                    onclick="openModal('modalViewFasilitas{{ $fasilitas->id_fasilitas }}')">
                                    <i class="fas fa-eye"></i>
                                </button>   
                                <button class="btn btn-edit"
                                    onclick="openModal('modalEditFasilitas{{ $fasilitas->id_fasilitas }}')">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-delete" onclick="confirmDelete({{ $fasilitas->id_fasilitas }})">
                                    <i class="fas fa-trash-alt"></i>
                                </button>

                                <form id="delete-form-{{ $fasilitas->id_fasilitas }}" 
                                    action="{{ route('fasilitas.destroy', $fasilitas->id_fasilitas) }}" 
                                    method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>

                        <!-- Modal View -->
                        <div id="modalViewFasilitas{{ $fasilitas->id_fasilitas }}" class="modal">
                            <div class="modal-content">
                                <span class="close" onclick="closeModal('modalViewFasilitas{{ $fasilitas->id_fasilitas }}')">&times;</span>
                                <h5>Detail Fasilitas</h5>
                                
                                <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                                    <!-- Kolom kiri -->
                                    <div style="width: 48%;">
                                        <label>Region</label>
                                        <input type="text" value="{{ $fasilitas->region->nama_region }}" readonly class="form-control">

                                        <label>Site</label>
                                        <input type="text" value="{{ $fasilitas->site->nama_site }}" readonly class="form-control">

                                        <label>No Rack</label>
                                        <input type="text" value="{{ $fasilitas->no_rack }}" readonly class="form-control">

                                        <label>Jenis Fasilitas</label>
                                        <input type="text" value="{{ $fasilitas->jenisfasilitas->nama_fasilitas }}" readonly class="form-control">
                                    </div>

                                    <!-- Kolom kanan -->
                                    <div style="width: 48%;">
                                        <label>Fasilitas ke-</label>
                                        <input type="text" value="{{ $fasilitas->fasilitas_ke }}" readonly class="form-control">

                                        <label>Brand</label>
                                        <input type="text" value="{{ optional($fasilitas->brandfasilitas)->nama_brand }}" readonly class="form-control">

                                        <label>Tipe</label>
                                        <input type="text" value="{{ $fasilitas->type }}" readonly class="form-control">

                                        <label>U Awal - U Akhir</label>
                                        <input type="text" value="{{ $fasilitas->uawal }} - {{ $fasilitas->uakhir }}" readonly class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>


                        {{-- Modal Edit --}}
                        <div id="modalEditFasilitas{{ $fasilitas->id_fasilitas }}" class="modal">
                            <div class="modal-content">
                                <span class="close"
                                    onclick="closeModal('modalEditFasilitas{{ $fasilitas->id_fasilitas }}')">&times;</span>
                                <h5>Edit Fasilitas</h5>
                                <form action="{{ route('fasilitas.update', $fasilitas->id_fasilitas) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label>Kode Region</label>
                                        <select name="kode_region" class="form-control regionSelectEdit"
                                            data-id="{{ $fasilitas->id_fasilitas }}" required>
                                            <option value="">Pilih Region</option>
                                            @foreach($regions as $region)
                                                <option value="{{ $region->kode_region }}" {{ $fasilitas->kode_region == $region->kode_region ? 'selected' : '' }}>
                                                    {{ $region->nama_region }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label>Kode Site</label>
                                        <select name="kode_site" class="form-control siteSelectEdit"
                                            data-id="{{ $fasilitas->id_fasilitas }}" required>
                                            <option value="">Pilih Site</option>
                                            @foreach($sites as $site)
                                                @if($site->kode_region == $fasilitas->kode_region)
                                                    <option value="{{ $site->kode_site }}" {{ $fasilitas->kode_site == $site->kode_site ? 'selected' : '' }}>
                                                        {{ $site->nama_site }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label>No Rack</label>
                                        <input type="text" name="no_rack" class="form-control"
                                            value="{{ $fasilitas->no_rack ?? '' }}">
                                    </div>
                                    <div class="mb-3">
                                        <label>Kode Fasilitas</label>
                                        <select name="kode_fasilitas" class="form-control" required>
                                            <option value="">Pilih Kode Fasilitas</option>
                                            @foreach($types as $jenisfasilitas)
                                                <option value="{{ $jenisfasilitas->kode_fasilitas }}" 
                                                    {{ $fasilitas->kode_fasilitas == $jenisfasilitas->kode_fasilitas ? 'selected' : '' }}>{{ $jenisfasilitas->nama_fasilitas }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>                   
                                    <div class="mb-3">
                                        <label>Kode Brand</label>
                                        <select name="kode_brand" class="form-control">
                                            <option value="">Pilih Kode Brand</option>
                                            @foreach($brands as $brandfasilitas)
                                                <option value="{{ $brandfasilitas->kode_brand }}" 
                                                    {{ $fasilitas->kode_brand == $brandfasilitas->kode_brand ? 'selected' : '' }}>
                                                    {{ $brandfasilitas->nama_brand }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label>Type</label>
                                        <input type="text" name="type" class="form-control" value="{{ $fasilitas->type ?? '' }}"
                                            >
                                    </div>
                                    <div class="mb-3">
                                        <label>U Awal</label>
                                        <input type="number" name="uawal" class="form-control"
                                            value="{{ $fasilitas->uawal ?? '' }}" >
                                    </div>
                                    <div class="mb-3">
                                        <label>U Akhir</label>
                                        <input type="number" name="uakhir" class="form-control"
                                            value="{{ $fasilitas->uakhir ?? '' }}" >
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
        <div id="modalTambahFasilitas" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('modalTambahFasilitas')">&times;</span>
                <h5>Tambah Fasilitas</h5>
                <form action="{{ route('fasilitas.store') }}" method="POST" id="formTambahFasilitas">
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
                <label>Kode Fasilitas</label>
                <select name="kode_fasilitas" class="form-control" required>
                    <option value="">Pilih Kode Fasilitas</option>
                    @foreach($types as $jenisfasilitas)
                        <option value="{{ $jenisfasilitas->kode_fasilitas }}">
                            {{ $jenisfasilitas->nama_fasilitas }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Kode Brand</label>
                <select name="kode_brand" class="form-control" >
                    <option value="">Pilih Kode Brand</option>
                    @foreach($brands as $brandfasilitas)
                        <option value="{{ $brandfasilitas->kode_brand }}">
                            {{ $brandfasilitas->nama_brand }}
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
        document.getElementById('formTambahFasilitas').addEventListener('submit', function (event) {
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
                const fasilitasId = this.getAttribute('data-id');
                const siteSelect = document.querySelector(`.siteSelectEdit[data-id="${fasilitasId}"]`);

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
        document.querySelectorAll('form[action*="fasilitas/update"]').forEach(form => {
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
