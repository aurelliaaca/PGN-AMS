@extends('layouts.app')

@section('title', 'Data Region')
@section('page_title', 'Data Region')

@section('content')
    <div class="main">
        <button class="btn btn-primary" style="margin-top: 20px; margin-bottom: 20px;"
            onclick="openModal('modalTambahRegion')">+ Tambah Region</button>
        <div class="card-grid">
            @foreach($regions as $region)
                <div class="toggle">
                    <div class="card-item">
                        <div class="card-content">
                            <h4>{{ $region->nama_region }}</h4>
                            <p>{{ $region->email }}</p>
                            <p>{{ $region->alamat }}</p>
                            <p>{{ $region->koordinat }}</p>

                            <div class="action-buttons">
                                <button class="btn btn-eye mb-3" onclick="toggleSites('{{ $region->kode_region }}')">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-edit mb-3"
                                    onclick="openModal('modalEditRegion{{ $region->id_region }}')">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-delete btn-sm" onclick="confirmDelete({{ $region->id_region }})">
                                    <i class="fas fa-trash-alt"></i>
                                </button>

                                <form id="delete-form-{{ $region->id_region }}" action="{{ route('region.destroy', $region->id_region) }}"
                                    method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <button class="btn btn-primary mb-3" style="width:125px"
                                    onclick="openModal('modalTambahSite{{ $region->id_region }}')">+ Tambah Site</button>
                            </div>
                        </div>
                        <div class="card-bigicon">
                            <i class="fa-solid fa-earth-americas"></i>
                        </div>
                    </div>
                    <div class="tables-container show">
                        <div id="sites{{ $region->kode_region }}" style="display: none;">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Nama Site</th>
                                            <th>Kode Site</th>
                                            <th>Jenis Site</th>
                                            <th>Kode Region</th>
                                            <th>Jumlah Rack</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($region->sites as $site)
                                            <tr>
                                                <td>{{ $site->nama_site }}</td>
                                                <td>{{ $site->kode_site }}</td>
                                                <td>{{ $site->jenis_site }}</td>
                                                <td>{{ $site->kode_region }}</td>
                                                <td>{{ $site->jml_rack }}</td>
                                                <td>
                                                    <!-- Edit Button -->
                                                    <button class="btn btn-edit"
                                                        onclick="openModal('modalEditSite{{ $site->id_site }}')">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-delete btn-sm" onclick="confirmDelete({{ $site->id_site }})">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>

                                                    <form id="delete-form-{{ $site->id_site }}"
                                                        action="{{ route('site.destroy', $site->id_site) }}" method="POST"
                                                        style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div id="modalTambahSite{{ $region->id_region }}" class="modal">
                        <div class="modal-content">
                            <span class="close" onclick="closeModal('modalTambahSite{{ $region->id_region }}')">&times;</span>
                            <h5>Tambah Site untuk Region {{ $region->nama_region }}</h5>
                            <form action="{{ route('site.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label>Nama Site</label>
                                    <input type="text" name="nama_site" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Kode Site</label>
                                    <input type="text" name="kode_site" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Jenis Site</label>
                                    <input type="text" name="jenis_site" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Kode Region</label>
                                    <input type="text" name="kode_region" class="form-control"
                                        value="{{ $region->kode_region }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label>Jumlah Rack</label>
                                    <input type="number" name="jml_rack" class="form-control">
                                </div>
                                <button type="submit" class="btn btn-primary">Tambah</button>
                            </form>
                        </div>
                    </div>


                    <!-- Modal Edit Site -->
                    @foreach($region->sites as $site)
                        <div id="modalEditSite{{ $site->id_site }}" class="modal">
                            <div class="modal-content">
                                <span class="close" onclick="closeModal('modalEditSite{{ $site->id_site }}')">&times;</span>
                                <h5>Edit Site</h5>
                                <form action="{{ route('site.update', $site->id_site) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label>Nama Site</label>
                                        <input type="text" name="nama_site" class="form-control" value="{{ $site->nama_site }}"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Kode Site</label>
                                        <input type="text" name="kode_site" class="form-control" value="{{ $site->kode_site }}"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Jenis Site</label>
                                        <input type="text" name="jenis_site" class="form-control" value="{{ $site->jenis_site }}"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Kode Region</label>
                                        <input type="text" name="kode_region" class="form-control" value="{{ $site->kode_region}}"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Jumlah Rack</label>
                                        <input type="number" name="jml_rack" class="form-control" value="{{ $site->jml_rack }}"
                                            required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
        <!-- Modal Tambah Region -->
        <div id="modalTambahRegion" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('modalTambahRegion')">&times;</span>
                <h5>Tambah Region</h5>
                <form action="{{ route('region.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label>Nama Region</label>
                        <input type="text" name="nama_region" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Kode Region</label>
                        <input type="text" name="kode_region" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Alamat</label>
                        <input type="text" name="alamat" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Koordinat</label>
                        <input type="text" name="koordinat" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </form>
            </div>
        </div>

        <!-- Modal Edit Region -->
        @foreach($regions as $region)
            <div id="modalEditRegion{{ $region->id_region }}" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal('modalEditRegion{{ $region->id_region }}')">&times;</span>
                    <h5>Edit Region</h5>
                    <form action="{{ route('region.update', $region->id_region) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label>Nama Region</label>
                            <input type="text" name="nama_region" class="form-control" value="{{ $region->nama_region }}"
                                required>
                        </div>
                        <div class="mb-3">
                            <label>Kode Region</label>
                            <input type="text" name="kode_region" class="form-control" value="{{ $region->kode_region }}"
                                readonly>
                        </div>
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="{{ $region->email }}" required>
                        </div>
                        <div class="mb-3">
                            <label>Alamat</label>
                            <input type="text" name="alamat" class="form-control" value="{{ $region->alamat }}">
                        </div>
                        <div class="mb-3">
                            <label>Koordinat</label>
                            <input type="text" name="koordinat" class="form-control" value="{{ $region->koordinat }}">
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <script>
        // Toggle the visibility of the sites table
        function toggleSites(regionCode) {
            const table = document.getElementById('sites' + regionCode);
            if (table.style.display === "none" || table.style.display === "") {
                table.style.display = "block";  // Show the table
            } else {
                table.style.display = "none";  // Hide the table
            }
        }

        // Function to close modal
        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.style.display = "none";
        }

        // Function to open modal
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.style.display = "block";
        }
    </script>
@endsection