@extends('layouts.app')
@section('title', 'Data User')
@section('page_title', 'Data User')

@section('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
    <div class="main">
        <button class="btn btn-primary mb-3" onclick="openModal('modalTambahUser')" style="margin-top: 20px;">
            + Tambah User
        </button>

        <div class="table-responsive" style="margin-top: 20px;">
            <table id="userTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Password</th>
                        <th>Role</th>
                        <th>Region</th>
                        <th>No Telepon</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span id="password-{{ $user->id }}" class="masked-password">••••••••</span>
                                <button type="button" class="btn btn-eye btn-secondary"
                                    onclick="togglePassword('{{ $user->id }}', '{{ $user->password }}')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                            <td>
                                @if($user->role == 1)
                                    Superadmin
                                @elseif($user->role == 2)
                                    Admin
                                @elseif($user->role == 3)
                                    User Internal
                                @elseif($user->role == 4)
                                    User Eksternal
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $user->region ?? '-' }}</td>
                            <td>{{ $user->mobile_number }}</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-edit btn-sm mb-1"
                                        onclick="openModal('modalEditUser{{ $user->id }}')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-delete btn-sm" onclick="confirmDelete({{ $user->id }})">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>

                                    <form id="delete-form-{{ $user->id }}" action="{{ route('user.destroy', $user->id) }}"
                                        method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <div id="modalEditUser{{ $user->id }}" class="modal">
                            <div class="modal-content">
                                <span class="close" onclick="closeModal('modalEditUser{{ $user->id }}')">&times;</span>
                                <h5>Edit User</h5>
                                <form action="{{ route('user.update', $user->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div style="display: flex; gap: 20px;">
                                        <div style="width: 48%;">
                                            <label>Nama</label>
                                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                                class="form-control" required>

                                            <label>Region</label>
                                            <select name="region" class="form-control" required>
                                                <option value="">Pilih Region</option>
                                                @foreach($regions as $region)
                                                    <option value="{{ $region->kode_region }}" {{ old('region', $user->region) == $region->kode_region ? 'selected' : '' }}>
                                                        {{ $region->nama_region }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            <label>Password</label>
                                            <input type="password" name="password" class="form-control">

                                            <label>Perusahaan</label>
                                            <input type="text" name="perusahaan"
                                                value="{{ old('perusahaan', $user->perusahaan) }}" class="form-control"
                                                required>

                                            <label>No KTP</label>
                                            <input type="text" name="noktp" value="{{ old('noktp', $user->noktp ?? '') }}"
                                                maxlength="16" class="form-control" required>
                                        </div>
                                        <div style="width: 48%;">
                                            <label>Email</label>
                                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                                class="form-control" required>

                                            <label>Role</label>
                                            <select name="role" class="form-control" required>
                                                <option value="" disabled {{ old('role', $user->role) ? '' : 'selected' }}>
                                                    Pilih Role
                                                </option>
                                                <option value="1" {{ old('role', $user->role) == 1 ? 'selected' : '' }}>Admin
                                                </option>
                                                <option value="2" {{ old('role', $user->role) == 2 ? 'selected' : '' }}>Operator
                                                    Aset</option>
                                                <option value="3" {{ old('role', $user->role) == 3 ? 'selected' : '' }}>Pengguna
                                                    Region</option>
                                                <option value="4" {{ old('role', $user->role) == 4 ? 'selected' : '' }}>Pengguna
                                                    Internal</option>
                                                <option value="5" {{ old('role', $user->role) == 5 ? 'selected' : '' }}>Pengguna
                                                    Eksternal</option>
                                            </select>

                                            <label>Alamat</label>
                                            <input type="text" name="alamat" value="{{ old('alamat', $user->alamat) }}"
                                                class="form-control" required>

                                            <label>Bagian</label>
                                            <input type="text" name="bagian" value="{{ old('bagian', $user->bagian) }}"
                                                class="form-control" required>

                                            <label>No Telepon</label>
                                            <input type="tel" name="mobile_number"
                                                value="{{ old('mobile_number', $user->mobile_number) }}" class="form-control"
                                                required>
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

        <div id="modalTambahUser" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('modalTambahUser')">&times;</span>
                <h5>Tambah User</h5>
                <form action="{{ route('user.store') }}" method="POST">
                    @csrf
                    <div style="display: flex; gap: 20px;">
                        <div style="width: 48%;">
                            <label>Nama</label>
                            <input type="text" name="name" class="form-control" required>

                            <label>Region</label>
                            <select name="region" class="form-control" required>
                                <option value="">Pilih Region</option>
                                @foreach($regions as $region)
                                    <option value="{{ $region->kode_region }}">{{ $region->nama_region }}</option>
                                @endforeach
                            </select>

                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>

                            <label>Perusahaan</label>
                            <input type="text" name="perusahaan" class="form-control" required>

                            <label>No KTP</label>
                            <input type="text" name="noktp" maxlength="16" class="form-control"
                                required>
                        </div>
                        <div style="width: 48%;">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>

                            <label>Role</label>
                            <select name="role" class="form-control" required>
                                <option value="" disabled selected>Pilih Role</option>
                                <option value="1">Admin</option>
                                <option value="2">Operator Aset</option>
                                <option value="3">Pengguna Region</option>
                                <option value="4">Pengguna Internal</option>
                                <option value="5">Pengguna Eksternal</option>
                            </select>

                            <label>Alamat</label>
                            <input type="text" name="alamat" class="form-control" required>

                            <label>Bagian</label>
                            <input type="text" name="bagian" class="form-control" required>

                            <label>No Telepon</label>
                            <input type="tel" name="mobile_number" class="form-control" required>
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
            $('#userTable').DataTable({
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
                        "targets": 7,
                        "orderable": false
                    }
                ]
            });
        });

        function togglePassword(userId, actualPassword) {
            const passwordSpan = document.getElementById('password-' + userId);
            const currentValue = passwordSpan.textContent;

            if (currentValue === '••••••••') {
                passwordSpan.textContent = actualPassword;
            } else {
                passwordSpan.textContent = '••••••••';
            }
        }
    </script>

@endsection