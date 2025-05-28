@extends('layouts.app')

@section('title', 'Profil')
@section('page_title', 'Profil')

@section('content')
    <div class="main" style="padding-top: 20px;">
        <div class="section">
            <header class="header-profile">
                <h2>{{ __('Informasi Profil') }}</h2>
                <p style="color: rgba(0, 0, 0, 0.5);">{{ __("Perbarui informasi profil akun Anda.") }}</p>
            </header>

            <div class="form-group" style="margin-bottom: 10px; padding-top: 20px;">
                <label for="name">Nama</label>
                <input id="name" type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" class="form-control" value="{{ auth()->user()->email }}" readonly>
            </div>

            <div class="button-wrapper">
                <button onclick="openModal('viewProfileModal')" class="btn btn-eye" style="font-size: 14px">Lihat
                    Profil</button>
                <button onclick="openModal('editProfileModal')" class="btn btn-primary">Edit Profil</button>
            </div>
        </div>

        <div class="modal" id="viewProfileModal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('viewProfileModal')">&times;</span>
                <h5>Lihat Profil</h5>
                <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                    <div style="width: 48%;">
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" class="form-control" value="{{ auth()->user()->email }}" readonly>
                        </div>
                        <div class="form-group">
                            <label>Perusahaan</label>
                            <input type="text" class="form-control" value="{{ auth()->user()->perusahaan }}" readonly>
                        </div>
                        <div class="form-group">
                            <label>Bagian</label>
                            <input type="text" class="form-control" value="{{ auth()->user()->bagian }}" readonly>
                        </div>
                    </div>
                    <div style="width: 48%;">
                        <div class="form-group">
                            <label>Region</label>
                            <input type="text" class="form-control" value="{{ auth()->user()->region }}" readonly>
                        </div>
                        <div class="form-group">
                            <label>No. KTP</label>
                            <input type="text" class="form-control" value="{{ auth()->user()->noktp }}" readonly>
                        </div>
                        <div class="form-group">
                            <label>Nomor HP</label>
                            <input type="text" class="form-control" value="{{ auth()->user()->mobile_number }}" readonly>
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
                            <input type="text" class="form-control" value="{{ auth()->user()->alamat }}" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="editProfileModal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('editProfileModal')">&times;</span>
                <h5>Edit Profil</h5>
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PATCH')
                    <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                        <div style="width: 48%;">
                            <label for="name">Nama</label>
                            <input type="text" id="name" name="name" class="form-control"
                                value="{{ old('name', auth()->user()->name) }}" required>

                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control"
                                value="{{ old('email', auth()->user()->email) }}" required>

                            <label for="perusahaan">Perusahaan</label>
                            <input type="text" id="perusahaan" name="perusahaan" class="form-control"
                                value="{{ old('perusahaan', auth()->user()->perusahaan) }}">

                            <label for="bagian">Bagian</label>
                            <input type="text" id="bagian" name="bagian" class="form-control"
                                value="{{ old('bagian', auth()->user()->bagian) }}">
                        </div>
                        <div style="width: 48%;">
                            <label for="region">Region</label>
                            <select id="region" name="region" class="form-control">
                                @foreach ($regions as $nama)
                                    <option value="{{ $nama }}" {{ old('region', auth()->user()->region) == $nama ? 'selected' : '' }}>
                                        {{ $nama }}
                                    </option>
                                @endforeach
                            </select>

                            <label for="noktp">No. KTP</label>
                            <input type="text" id="noktp" name="noktp" class="form-control"
                                value="{{ old('noktp', auth()->user()->noktp) }}">

                            <label for="mobile_number">Nomor HP</label>
                            <input type="text" id="mobile_number" name="mobile_number" class="form-control"
                                value="{{ old('mobile_number', auth()->user()->mobile_number) }}">

                            <label for="alamat">Alamat</label>
                            <input type="text" id="alamat" name="alamat" class="form-control"
                                value="{{ old('alamat', auth()->user()->alamat) }}">

                            <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Simpan Perubahan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>            

        @if(auth()->user()->role == 1)
            <div class="section">
                <header class="header-profile">
                    <h2>{{ __('Tanda Tangan') }}</h2>
                    <p style="color: rgba(0, 0, 0, 0.5);">{{ __('Unggah tanda tangan digital Anda di sini.') }}</p>
                </header>

                <form action="{{ route('users.signature.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="signature" accept="image/*" class="form-control">
                    @if ($errors->has('signature'))
                        <div class="error">{{ $errors->first('signature') }}</div>
                    @endif
                    <button type="submit" class="btn-primary">{{ __('Unggah') }}</button>
                </form>

                @if (auth()->user()->signature)
                    <div style="padding-top: 20px;">
                        <p>{{ __('Pratinjau tanda tangan Anda:') }}</p>
                        <img src="{{ asset('storage/' . auth()->user()->signature) }}" alt="Signature" class="signature-preview"
                            style="max-width: 200px; height: auto;">
                    </div>
                @endif
            </div>
        @endif

        <div class="section">
            <header class="header-profile">
                <h2>{{ __('Perbarui Kata Sandi') }}</h2>
                <p style="color: rgba(0, 0, 0, 0.5);">
                    {{ __('Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap aman.') }}
                </p>
            </header>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password">{{ __('Kata Sandi Saat Ini') }}</label>
                    <input id="current_password" name="current_password" type="password" class="form-control"
                        autocomplete="current-password">
                    @if ($errors->updatePassword->has('current_password'))
                        <p class="error">{{ $errors->updatePassword->first('current_password') }}</p>
                    @endif
                </div>

                <div>
                    <label for="password">{{ __('Kata Sandi Baru') }}</label>
                    <input id="password" name="password" type="password" class="form-control" autocomplete="new-password">
                    @if ($errors->updatePassword->has('password'))
                        <p class="error">{{ $errors->updatePassword->first('password') }}</p>
                    @endif
                </div>

                <div>
                    <label for="password_confirmation">{{ __('Konfirmasi Kata Sandi') }}</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" class="form-control"
                        autocomplete="new-password">
                    @if ($errors->updatePassword->has('password_confirmation'))
                        <p class="error">{{ $errors->updatePassword->first('password_confirmation') }}</p>
                    @endif
                </div>

                <div style="text-align: right;">
                    <a href="{{ route('password.request') }}"
                        style="font-size: 16px; color: #4f52ba; text-decoration: underline;">
                        {{ __('Lupa kata sandi?') }}
                    </a>
                </div>

                <div>
                    <button type="submit" class="btn-primary">{{ __('Simpan') }}</button>
                </div>
            </form>
        </div>

        <div class="section">
            <header class="header-profile">
                <h2>{{ __('Hapus Akun') }}</h2>
                <p style="color: rgba(0, 0, 0, 0.5);">
                    {{ __('Setelah akun Anda dihapus, semua data dan sumber daya terkait akan dihapus secara permanen.') }}
                </p>
            </header>

            <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                class="btn-primary" style="margin-top: 20px;">
                {{ __('Hapus Akun') }}
            </button>

            <div x-data="{ show: {{ $errors->userDeletion->isNotEmpty() ? 'true' : 'false' }} }" x-show="show" x-transition
                class="modal">
                <div class="modal-content">
                    <span class="modal-close" x-on:click="$dispatch('close'); show = false">×</span>
                    <form method="POST" action="{{ route('profile.destroy') }}">
                        @csrf
                        @method('DELETE')

                        <h2>{{ __('Apakah Anda yakin ingin menghapus akun?') }}</h2>
                        <p>{{ __('Setelah akun dihapus, data Anda tidak bisa dipulihkan.') }}</p>

                        <label for="password_delete">{{ __('Masukkan kata sandi Anda untuk konfirmasi') }}</label>
                        <input id="password_delete" name="password_delete" type="password" class="form-control" required>
                        @if ($errors->userDeletion->has('password_delete'))
                            <p class="error">{{ $errors->userDeletion->first('password_delete') }}</p>
                        @endif

                        <div style="margin-top: 20px; text-align: right;">
                            <button type="button" x-on:click="$dispatch('close'); show = false"
                                class="btn-secondary">{{ __('Batal') }}</button>
                            <button type="submit" class="btn-danger">{{ __('Hapus Akun') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection