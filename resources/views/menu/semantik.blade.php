@extends('layouts.app')
@section('title', 'Semantik') {{-- Ini buat title di tab browser --}}
@section('page_title', 'Semantik') {{-- Ini buat judul yang tampil di halaman --}}
@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <head>
        <style>
            .photo-gallery {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                border-radius: 12px;
                padding: 10px;
                box-sizing: border-box;
                margin-top: 10px;
                width: 100%;
            }

            .photo-wrapper {
                width: calc(25% - 10px);
                border-radius: 12px;
                border: 2px solid rgb(209, 210, 241);
                padding: 8px;
            }

            .photo-img {
                width: 100%;
                height: auto;
                aspect-ratio: 4/3;
                object-fit: cover;
                border-radius: 8px;
                display: block;
            }
        </style>
    </head>
    <div class="main">
        <div class="button-wrapper" style="margin-top: 20px; margin-bottom: 20px;">
            <button class="btn btn-primary mb-3" onclick="showModal('uploadModal')">+ Tambah Foto</button>
        </div>

        <div id="uploadModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('uploadModal')">&times;</span>
                <h5>Upload Foto</h5>
                <form id="uploadPhotoForm" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="photo">Pilih Foto</label>
                        <input type="file" id="photo" name="photo" accept="image/*" required>
                    </div>
                    <div class="mb-3">
                        <label>Judul</label>
                        <input type="text" name="title" class="form-control" value="" required>
                    </div>
                    <div class="mb-3">
                        <label>Teks</label>
                        <textarea type="text" name="text" rows="3" class="form-control" value=""></textarea>
                    </div>
                    <div style="text-align: right;">
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="photoGallery" class="photo-gallery">
            @foreach($photos as $photo)
                <div class="photo-wrapper">
                    <div class="card-content">
                        <img src="{{ asset($photo->file_path) }}" alt="{{ $photo->title }}"
                            onclick="showImage('{{ asset($photo->file_path) }}', '{{ $photo->title }}')" class="photo-img">
                        <h2 style="margin-top: 10px; margin-bottom: 3px;">{{ $photo->title }}</h2>
                        <h5>{{ $photo->text }}</h5>
                        <h5 class="text-muted">
                            Diunggah pada
                            {{ \Carbon\Carbon::parse($photo->created_at)->translatedFormat('j F Y H:i') }}
                        </h5>
                        <form action="{{ route('photos.delete', $photo->id) }}" method="POST" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-delete" style="margin-top: 10px;"
                                data-title="{{ $photo->title }}">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>


    <div id="imageModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('imageModal')">&times;</span>
            <h5 id="modalImageTitle" style="text-align: center;"></h5>
            <img id="modalImage" src="" alt="Foto Besar"
                style="width: 100%; height: auto; max-height: 80vh; object-fit: contain;">
        </div>
    </div>
    </div>
    <script>
        document.getElementById('uploadPhotoForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('/upload-photo', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => {
                            throw new Error(text);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const photoGallery = document.getElementById('photoGallery');
                        const card = `
                                                                    <div class="col-md-3 mb-4">
                                                                        <div class="card">
                                                                            <img class="card-img-top" src="${data.photoUrl}" alt="Card image cap" style="height: 150px; object-fit: cover;" onclick="showImage('${data.photoUrl}', '${data.title}')">
                                                                            <div class="card-body">
                                                                                <h5 class="card-title">${data.title}</h5>
                                                                                <p class="card-text">${data.text}</p>
                                                                            </div>
                                                                            <div class="card-footer">
                                                                                <small class="text-muted">Uploaded on: ${data.timestamp}</small>
                                                                                <form action="/photos/${data.id}" method="POST" style="display:inline;">
                                                                                    @csrf
                                                                                    @method('DELETE')
                                                                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                `;
                        photoGallery.insertAdjacentHTML('beforeend', card);
                        closeModal('uploadModal'); // Menutup modal upload
                        location.reload(); // Reload halaman untuk memperbarui galeri foto
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Gagal mengupload foto: ' + data.message,
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi kesalahan!',
                        text: 'Kesalahan saat mengupload foto: ' + error.message,
                    });
                });
        });

        function showImage(imageUrl, title) {
            console.log(imageUrl);
            document.getElementById('modalImage').src = imageUrl;
            document.getElementById('modalImageTitle').innerText = title;
            document.getElementById('imageModal').style.display = 'block';

            // Sembunyikan elemen yang ingin disembunyikan
            document.querySelectorAll('.header, .card-grid').forEach(element => {
                element.classList.add('hidden'); // Menambahkan kelas hidden
            });
        }

        // Close modal when clicking outside of the modal content
        window.onclick = function (event) {
            if (event.target == document.getElementById('uploadModal') || event.target == document.getElementById('imageModal')) {
                closeModal('uploadModal');
                closeModal('imageModal');
            }
        }

        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function () {
                const form = this.closest('.delete-form');
                const photoTitle = this.getAttribute('data-title');
                Swal.fire({
                    title: 'Apakah kamu yakin?',
                    text: `Kamu akan menghapus foto: "${photoTitle}"`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        function showModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
    </script>
@endsection