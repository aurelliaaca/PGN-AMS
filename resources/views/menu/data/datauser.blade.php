@extends('layouts.app')
@section('title', 'Data User')
@section('page_title', 'Data User')

@section('content')
<div class="main">
    <button class="btn btn-primary mb-3" onclick="openModal('modalTambahUser')" style="margin-top: 20px;">+ Tambah User</button>

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
                        <button type="button" class="btn btn-eye btn-secondary" onclick="togglePassword('{{ $user->id }}', '{{ $user->password }}')">
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
                            <!-- <button class="btn btn-eye btn-sm mb-1"
                                onclick="openModal('modalViewUser{{ $user->id }}')">
                                <i class="fas fa-eye"></i>
                            </button> -->
                            <button class="btn btn-edit btn-sm mb-1"
                                onclick="openModal('modalEditUser{{ $user->id }}')">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-delete btn-sm"
                                onclick="confirmDelete({{ $user->id }})">
                                <i class="fas fa-trash-alt"></i>
                            </button>

                            <form id="delete-form-{{ $user->id }}" 
                                  action="{{ route('user.destroy', $user->id) }}" 
                                  method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </td>
                </tr>

                <!-- <div id="modalViewUser{{ $user->id }}" class="modal">
                    <div class="modal-content">
                        <span class="close" onclick="closeModal('modalViewUser{{ $user->id }}')">&times;</span>
                        <h5>Detail User</h5>
                        <label>Nama</label>
                        <input type="text" value="{{ $user->name }}" readonly class="form-control">

                        <label>Email</label>
                        <input type="text" value="{{ $user->email }}" readonly class="form-control">

                        <label>Role</label>
                        <input type="text" value="{{ $user->role }}" readonly class="form-control">

                        <label>Region</label>
                        <input type="text" value="{{ $user->region ?? '-' }}" readonly class="form-control">

                        <label>No Telepon</label>
                        <input type="text" value="{{ $user->mobile_number }}" readonly class="form-control">
                    </div>
                </div> -->

                <div id="modalEditUser{{ $user->id }}" class="modal">
                    <div class="modal-content">
                        <span class="close" onclick="closeModal('modalEditUser{{ $user->id }}')">&times;</span>
                        <h5>Edit User</h5>
                        <form action="{{ route('user.update', $user->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <label>Nama</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>

                            <label>Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>

                            <label>Password <small>(Kosongkan jika tidak ingin mengubah)</small></label>
                            <input type="password" name="password" class="form-control">

                            <label>Role</label>
                            <select name="role" class="form-control" required>
                                <option value="" disabled selected>Pilih Role</option>
                                <option value="1" {{ $user->role == 1 ? 'selected' : '' }}>Superadmin</option>
                                <option value="2" {{ $user->role == 2 ? 'selected' : '' }}>Admin</option>
                                <option value="3" {{ $user->role == 3 ? 'selected' : '' }}>User Internal</option>
                                <option value="4" {{ $user->role == 4 ? 'selected' : '' }}>User Eksternal</option>
                            </select>

                            <label>Region</label>
                            <input type="text" name="region" value="{{ old('region', $user->region) }}" class="form-control">

                            <label>No Telepon</label>
                            <input type="text" name="mobile_number" value="{{ old('mobile_number', $user->mobile_number) }}" class="form-control">

                            <button type="submit" class="btn btn-primary mt-3">Perbarui</button>
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
                <label>Nama</label>
                <input type="text" name="name" class="form-control" required>

                <label>Email</label>
                <input type="email" name="email" class="form-control" required>

                <label>Password</label>
                <input type="password" name="password" class="form-control" required>

                <label>Role</label>
                <select name="role" class="form-control" required>
                    <option value="" disabled selected>Pilih Role</option>
                    <option value="1">Superadmin</option>
                    <option value="2">Admin</option>
                    <option value="3">User Internal</option>
                    <option value="4">User Eksternal</option>
                </select>


                <label>Region</label>
                <input type="text" name="region" class="form-control">

                <label>No Telepon</label>
                <input type="text" name="mobile_number" class="form-control">

                <button type="submit" class="btn btn-primary mt-3">Simpan</button>
            </form>
        </div>
    </div>
</div>
<script>
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
