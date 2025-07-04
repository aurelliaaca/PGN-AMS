@extends('layouts.app')

@section('title', 'Aset Perangkat')
@section('page_title', 'Aset Perangkat')

@section('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
    <div class="main">
        @if(auth()->user()->role == '1' || auth()->user()->role == '2')
        <div class="button-wrapper" style="margin-top: 20px;">
            <button class="btn btn-primary mb-3" onclick="openModal('modalTambahPerangkat')">+ Tambah Perangkat</button>
            <button type="button" class="btn btn-primary mb-3" onclick="openModal('importModal')">Impor Data Perangkat</button>
            <button type="button" class="btn btn-primary mb-3" onclick="openModal('exportModal')">Ekspor Data Perangkat</button>
        </div>

        <form method="GET" action="{{ route('perangkat.index') }}" id="filterForm">
        <div class="filter-container" style="margin-top: 20px;">
                <select name="kode_region[]" class="select2" multiple data-placeholder="Pilih Region" onchange="document.getElementById('filterForm').submit()">
                    @foreach($regions as $region)
                        <option value="{{ $region->kode_region }}" {{ in_array($region->kode_region, request('kode_region', [])) ? 'selected' : '' }}>
                             {{ $region->nama_region }}
                        </option>
                    @endforeach
                </select>

                <select name="kode_site[]" class="select2" multiple 
                        data-placeholder="Pilih Site"
                        {{ request()->filled('kode_region') ? '' : 'disabled' }}>
                    @foreach($filteredSites as $site)
                        <option value="{{ $site->kode_site }}" 
                                {{ in_array($site->kode_site, request('kode_site', [])) ? 'selected' : '' }}>
                            {{ $site->nama_site }}
                        </option>
                    @endforeach
                </select>

                <select name="kode_perangkat[]" class="select2" multiple data-placeholder="Pilih Perangkat" onchange="document.getElementById('filterForm').submit()">
                @foreach($types as $type)
                        <option value="{{ $type->kode_perangkat }}" {{ in_array($type->kode_perangkat, request('kode_perangkat', [])) ? 'selected' : '' }}>
                            {{ $type->nama_perangkat }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
        @endif

        <div class="table-responsive" style="margin-top: 20px;">
            <table id="perangkatTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th></th>
                        <th>No</th>
                        <th>Hostname</th>
                        <th>Region</th>
                        <th>Site</th>
                        <th>No Rack</th>
                        <th>Perangkat</th>
                        <th>Brand</th>
                        <th>Type</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dataperangkat as $perangkat)
                        <tr>
                            <td>
                                <div class="status-box {{ $perangkat->no_rack ? 'bg-success' : 'bg-danger' }}"></div>
                            </td>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                {{
                                    implode('-', array_filter([
                                        $perangkat->kode_region,
                                        $perangkat->kode_site,
                                        $perangkat->no_rack,
                                        $perangkat->kode_perangkat,
                                        $perangkat->perangkat_ke,
                                        $perangkat->kode_brand,
                                        $perangkat->type
                                    ]))
                                }}
                            </td>
                            <td>{{ $perangkat->region->nama_region }}</td>
                            <td>{{ $perangkat->site->nama_site }}</td>
                            <td>{{ $perangkat->no_rack }}</td>
                            <td>{{ $perangkat->jenisperangkat->nama_perangkat }}</td>
                            <td>{{ optional($perangkat->brandperangkat)->nama_brand }}</td>
                            <td>{{ $perangkat->type }}</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-eye btn-sm mb-1"
                                        onclick="openModal('modalViewPerangkat{{ $perangkat->id_perangkat }}')">
                                        <i class="fas fa-eye"></i> 
                                    </button>   
                                    @if(auth()->user()->role == '1' || auth()->user()->role == '2')
                                    <button class="btn btn-edit btn-sm mb-1"
                                        onclick="openModal('modalEditPerangkat{{ $perangkat->id_perangkat }}')">
                                        <i class="fas fa-edit"></i> 
                                    </button>
                                    <button class="btn btn-delete btn-sm"
                                        onclick="confirmDelete({{ $perangkat->id_perangkat }})">
                                        <i class="fas fa-trash-alt"></i> 
                                    </button>

                                    <form id="delete-form-{{ $perangkat->id_perangkat }}" 
                                        action="{{ route('perangkat.destroy', $perangkat->id_perangkat) }}" 
                                        method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        <div id="modalViewPerangkat{{ $perangkat->id_perangkat }}" class="modal">
                            <div class="modal-content">
                                <span class="close" onclick="closeModal('modalViewPerangkat{{ $perangkat->id_perangkat }}')">&times;</span>
                                <h5>Detail Perangkat</h5>
                                
                                <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                                    <div style="width: 48%;">
                                        <label>Region</label>
                                        <input type="text" value="{{ $perangkat->region->nama_region }}" readonly class="form-control">
                                                                              
                                        <label>Jenis</label>
                                        <input type="text" value="{{ $perangkat->jenisperangkat->nama_perangkat }}" readonly class="form-control">
                                        
                                        <label>No Rack</label>
                                        <input type="text" value="{{ $perangkat->no_rack }}" readonly class="form-control">

                                        <label>U Awal - U Akhir</label>
                                        <input type="text" value="{{ $perangkat->uawal }} - {{ $perangkat->uakhir }}" readonly class="form-control">

                                        <label>Perangkat ke-</label>
                                        <input type="text" value="{{ $perangkat->perangkat_ke }}" readonly class="form-control">
                                    </div>

                                    <div style="width: 48%;">
                                        <label>Site</label>
                                        <input type="text" value="{{ $perangkat->site->nama_site }}" readonly class="form-control">

                                        <label>Brand</label>
                                        <input type="text" value="{{ optional($perangkat->brandperangkat)->nama_brand }}" readonly class="form-control">

                                        <label>Milik</label>
                                        <input type="text" value="{{ $perangkat->user->name }}" readonly class="form-control">
    
                                        <label>Type</label>
                                        <input type="text" value="{{ $perangkat->type }}" readonly class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div id="modalEditPerangkat{{ $perangkat->id_perangkat }}" class="modal">
                            <div class="modal-content">
                                <span class="close"
                                    onclick="closeModal('modalEditPerangkat{{ $perangkat->id_perangkat }}')">&times;</span>
                                <h5>Edit Perangkat</h5>
                                <form action="{{ route('perangkat.update', $perangkat->id_perangkat) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                                        <div style="width: 48%;">
                                            <div class="mb-3">
                                                <label>Region</label>
                                                <select name="kode_region" class="form-control regionSelectEdit" style="padding: 5px;"
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
                                                <label>Jenis</label>
                                                <select name="kode_perangkat" class="form-control" required>
                                                    <option value="">Pilih Perangkat</option>
                                                    @foreach($types as $jenisperangkat)
                                                        <option value="{{ $jenisperangkat->kode_perangkat }}" 
                                                            {{ $perangkat->kode_perangkat == $jenisperangkat->kode_perangkat ? 'selected' : '' }}>{{ $jenisperangkat->nama_perangkat }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>  
                                            <label>No Rack</label>
                                            <select name="no_rack" class="form-control noRackSelectEdit"
                                                data-id="{{ $perangkat->id_perangkat }}">
                                                <option value="">Pilih No Rack</option>
                                                @foreach($racks as $rack)
                                                    @if($rack->kode_region == $perangkat->kode_region && $rack->kode_site == $perangkat->kode_site)
                                                        <option value="{{ $rack->no_rack }}" {{ $perangkat->no_rack == $rack->no_rack ? 'selected' : '' }}>
                                                            {{ $rack->no_rack }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
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
                                        </div>
                                        <div style="width: 48%;">
                                            <div class="mb-3">
                                                <label>Site</label>
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
                                                <label>Brand</label>
                                                <select name="kode_brand" class="form-control">
                                                    <option value="">Pilih Brand</option>
                                                    @foreach($brands as $brandperangkat)
                                                        <option value="{{ $brandperangkat->kode_brand }}" 
                                                            {{ $perangkat->kode_brand == $brandperangkat->kode_brand ? 'selected' : '' }}>
                                                            {{ $brandperangkat->nama_brand }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label>Milik</label>
                                                <select name="milik" class="form-control" required>
                                                    <option value="">Pilih Kepemilikan</option>
                                                    @foreach($users as $milik)
                                                        <option value="{{ $milik->id }}" 
                                                            {{ $perangkat->milik == $milik->id ? 'selected' : '' }}>{{ $milik->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label>Type</label>
                                                <input type="text" name="type" class="form-control" value="{{ $perangkat->type ?? '' }}"
                                                    >
                                            </div>
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
                <h5>Impor Data Perangkat</h5>
                <form action="{{ route('import.perangkat') }}" method="POST" enctype="multipart/form-data">
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
                <span class="close" onclick="closeModal('exportModal')">&times;</span>
                <h5>Ekspor Data Perangkat</h5>
                <form id="exportForm" action="{{ url('export/perangkat') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="regions">Pilih Region:</label>
                        <div id="regions">
                            @foreach ($regions as $region)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="regions[]" value="{{ $region['kode_region'] }}" id="region-{{ $loop->index }}">
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

        <div id="modalTambahPerangkat" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('modalTambahPerangkat')">&times;</span>
                <h5>Tambah Perangkat</h5>

                <form action="{{ route('perangkat.store') }}" method="POST" id="formTambahPerangkat">
                    @csrf
                    <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                        <div style="width: 48%;">
                            <div class="mb-3">
                                <label>Region</label>
                                <select id="regionSelectTambah" name="kode_region" class="form-control" required>
                                    <option value="">Pilih Region</option>
                                    @foreach($regions as $region)
                                        <option value="{{ $region->kode_region }}">{{ $region->nama_region }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label>Jenis</label>
                                <select name="kode_perangkat" class="form-control" required>
                                    <option value="">Pilih Perangkat</option>
                                    @foreach($types as $jenisperangkat)
                                        <option value="{{ $jenisperangkat->kode_perangkat }}">
                                            {{ $jenisperangkat->nama_perangkat }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label>No Rack</label>
                                <select id="noRackSelectTambah" name="no_rack" class="form-control" disabled>
                                    <option value="">Pilih Rack</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label>U Awal</label>
                                <input type="number" name="uawal" class="form-control" id="uawal" value="">
                            </div>

                            <div class="mb-3">
                                <label>U Akhir</label>
                                <input type="number" name="uakhir" class="form-control" id="uakhir" value="">
                            </div>
                            
                        </div>

                        <div style="width: 48%;">
                            <div class="mb-3">
                                <label>Site</label>
                                <select id="siteSelectTambah" name="kode_site" class="form-control" required disabled>
                                    <option value="">Pilih Site</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label>Brand</label>
                                <select name="kode_brand" class="form-control">
                                    <option value="">Pilih Brand</option>
                                    @foreach($brands as $brandperangkat)
                                        <option value="{{ $brandperangkat->kode_brand }}">
                                            {{ $brandperangkat->nama_brand }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label>Milik</label>
                                <select name="milik" class="form-control" required>
                                    <option value="">Pilih Kepemilikan</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label>Type</label>
                                <input type="text" name="type" class="form-control" value="">
                            </div>

                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Tambah</button>
                </form>
            </div>
        </div>
    </div>
</div>

    @section('scripts')
        <script>
            $(document).ready(function() {
                $('.select2').select2({
                    width: '100%',
                    placeholder: function() {
                        return $(this).data('placeholder');
                    },
                    allowClear: true
                });
                $('select[name="kode_region[]"]').on('change', function() {
                    const selectedRegions = $(this).val();
                    const siteSelect = $('select[name="kode_site[]"]');
                    const currentlySelectedSites = siteSelect.val(); 
                    
                    siteSelect.empty().prop('disabled', true);
                    
                    if (selectedRegions && selectedRegions.length > 0) {
                        siteSelect.prop('disabled', false);
                        
                        const sites = @json($sites);
                        const filteredSites = sites.filter(site => 
                            selectedRegions.includes(site.kode_region)
                        );
                        
                        filteredSites.forEach(site => {
                            const option = new Option(site.nama_site, site.kode_site);
                            siteSelect.append(option);
                        });

                        if (currentlySelectedSites) {
                            const validSites = currentlySelectedSites.filter(site => 
                                filteredSites.some(fs => fs.kode_site === site)
                            );
                            siteSelect.val(validSites);
                        }
                    }
                    siteSelect.select2({
                        width: '100%',
                        placeholder: 'Pilih Site',
                        allowClear: true
                    });
                    $('#filterForm').submit();
                });

                $('#perangkatTable').DataTable({
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
                    pageLength: 10,
                    lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Semua"]],
                    order: [], 
                    columnDefs: [
                        { targets: [0, 9], orderable: false }
                    ]
                });
            });

            document.getElementById('regionSelectTambah').addEventListener('change', function() {
                const regionId = this.value;
                const siteSelect = document.getElementById('siteSelectTambah');

                siteSelect.innerHTML = '<option value="">Pilih Site</option>';
                siteSelect.disabled = true;

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
            
            document.getElementById('siteSelectTambah').addEventListener('change', function() {
                const siteId = this.value;
                const regionId = document.getElementById('regionSelectTambah').value;
                const rackSelect = document.getElementById('noRackSelectTambah');

                rackSelect.innerHTML = '<option value="">Pilih Rack</option>';
                rackSelect.disabled = true;

                if (regionId && siteId) {
                    rackSelect.disabled = false;
                    
                    const racks = @json($racks); 
                    const filteredRacks = racks.filter(rack => rack.kode_region == regionId && rack.kode_site == siteId);

                    filteredRacks.forEach(rack => {
                        const option = document.createElement('option');
                        option.value = rack.no_rack;
                        option.textContent = rack.no_rack;
                        rackSelect.appendChild(option);
                    });
                }
            });

            document.querySelectorAll('.regionSelectEdit').forEach(select => {
                select.addEventListener('change', function () {
                    const regionId = this.value;
                    const fasilitasId = this.getAttribute('data-id');
                    const siteSelect = document.querySelector(`.siteSelectEdit[data-id="${fasilitasId}"]`);
                    const rackSelect = document.querySelector(`.noRackSelectEdit[data-id="${fasilitasId}"]`);

                    siteSelect.innerHTML = '<option value="">Pilih Site</option>';
                    rackSelect.innerHTML = '<option value="">Pilih No Rack</option>';
                    siteSelect.disabled = true;
                    rackSelect.disabled = true;

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

            document.querySelectorAll('.siteSelectEdit').forEach(select => {
                select.addEventListener('change', function () {
                    const siteId = this.value;
                    const fasilitasId = this.getAttribute('data-id');
                    const regionId = document.querySelector(`.regionSelectEdit[data-id="${fasilitasId}"]`).value;
                    const rackSelect = document.querySelector(`.noRackSelectEdit[data-id="${fasilitasId}"]`);

                    rackSelect.innerHTML = '<option value="">Pilih No Rack</option>';
                    rackSelect.disabled = true;

                    if (regionId && siteId) {
                        rackSelect.disabled = false;
                        const racks = @json($racks);
                        const filteredRacks = racks.filter(rack => rack.kode_region == regionId && rack.kode_site == siteId);

                        filteredRacks.forEach(rack => {
                            const option = document.createElement('option');
                            option.value = rack.no_rack;
                            option.textContent = rack.no_rack;
                            rackSelect.appendChild(option);
                        });
                    }
                });
            });

            // document.getElementById('no_rack').addEventListener('input', function () {
            //     const noRack = this.value;
            //     const uawalField = document.getElementById('uawal');
            //     const uakhirField = document.getElementById('uakhir');

            //     if (noRack) {
            //         uawalField.setAttribute('required', 'required');
            //         uakhirField.setAttribute('required', 'required');
            //     } else {
            //         uawalField.removeAttribute('required');
            //         uakhirField.removeAttribute('required');
            //     }
            // });

            // document.getElementById('formTambahPerangkat').addEventListener('submit', function (event) {
            //     const uawal = parseFloat(document.getElementById('uawal').value);
            //     const uakhir = parseFloat(document.getElementById('uakhir').value);

            //     if (uawal >= uakhir) {
            //         alert('U Awal harus lebih kecil dari U Akhir.');
            //         event.preventDefault(); 
            //     }

            //     if (uawal < 0 || uakhir < 0) {
            //         alert('U Awal dan U Akhir tidak boleh bernilai negatif.');
            //         event.preventDefault(); 
            //     }
            // });

            // document.querySelectorAll('form[action*="perangkat/update"]').forEach(form => {
            //     form.addEventListener('submit', function(event) {
            //         const uawal = parseFloat(this.querySelector('input[name="uawal"]').value);
            //         const uakhir = parseFloat(this.querySelector('input[name="uakhir"]').value);
            //         const noRack = this.querySelector('input[name="no_rack"]').value;

            //         if (noRack && (!uawal || !uakhir)) {
            //             alert('U Awal dan U Akhir wajib diisi jika No Rack diisi.');
            //             event.preventDefault();
            //             return;
            //         }

            //         if (uawal >= uakhir) {
            //             alert('U Awal harus lebih kecil dari U Akhir.');
            //             event.preventDefault();
            //         }

            //         if (uawal < 0 || uakhir < 0) {
            //             alert('U Awal dan U Akhir tidak boleh bernilai negatif.');
            //             event.preventDefault();
            //         }
            //     });
            // });
        </script>
    @endsection
@endsection
