<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title'); ?></title>

    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> -->
</head>

<link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">

<body>
    <aside class="sidebar">
        <div class="sidebar-title">
            <img src="<?php echo e(asset('img/pgncom.png')); ?>" alt="logo" />
            <h2>AMS</h2>
        </div>
        <ul class="sidebar-links">
            <h4>
                <span>Menu</span>
                <div class="menu-separator"></div>
            </h4>
            <li>
                <a href="<?php echo e(route('home')); ?>">
                    <span class="icon"><i class="fas fa-tachometer-alt"></i></span>
                    <span class="text">Dasbor</span>
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('data')); ?>">
                    <span class="icon"><i class="fas fa-database"></i></span>
                    <span class="text">Data</span>
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('rack.index')); ?>">
                    <span class="icon"><i class="fas fa-server"></i></span>
                    <span class="text">Rack</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <span class="icon"><i class="fas fa-image"></i></span>
                    <span class="text">Semantik</span>
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('histori.index')); ?>">
                    <span class="icon"><i class="fas fa-history"></i></span>
                    <span class="text">Histori</span>
                </a>
            </li>
            <?php if(auth()->user()->role == '1' || auth()->user()->role == '2'): ?>
                <h4>
                    <span>Aset</span>
                    <div class="menu-separator"></div>
                </h4>
                <li>
                    <a href="<?php echo e(route('perangkat.index')); ?>">
                        <span class="icon"><i class="fas fa-tools"></i></span>
                        <span class="text">Perangkat</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('perangkat.index')); ?>">
                        <span class="icon"><i class="fas fa-warehouse"></i></span>
                        <span class="text">Fasilitas</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="icon"><i class="fas fa-ruler"></i></span>
                        <span class="text">Alat Ukur</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="icon"><i class="fas fa-network-wired"></i></span>
                        <span class="text">Jaringan</span>
                    </a>
                </li>
            <?php endif; ?>
            <h4>
                <span>Portal VMS</span>
                <div class="menu-separator"></div>
            </h4>
            <li>
                <a href="<?php echo e(auth()->user()->role == 1 ? route('verifikasi.superadmin.index') : route('verifikasi.user.index')); ?>">
                    <span class="icon"><i class="fas fa-tools"></i></span>
                    <span class="text">VMS</span>
                </a>
            </li>
            <h4>
                <span>Akun</span>
                <div class="menu-separator"></div>
            </h4>
            <li>
                <a href="#">
                    <span class="icon"><i class="fas fa-user-circle"></i></span>
                    <span class="text">Profil</span>
                </a>
            </li>

            <li>
                <form action="<?php echo e(route('logout')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button type="submit">
                        <span class="icon"><i class="fas fa-sign-out-alt" style="margin-left:5px"></i></span>
                        <span class="text" style="margin-left:-2px">Keluar</span>
                    </button>
                </form>
            </li>
        </ul>

        <div class="user-account">
            <div class="user-profile">
                <img src="<?php echo e(asset('img/profile-default.png')); ?>" alt="Profile Image" />
                <div class="user-detail">
                    <h3><?php echo e(auth()->user()->name); ?></h3>
                    <?php
                        $roleText = [
                            1 => 'Superadmin',
                            2 => 'Admin',
                            3 => 'Guest',
                        ][auth()->user()->role] ?? 'Unknown'; // Default 'Unknown' jika role tidak dikenali
                    ?>
                    <span><?php echo e($roleText); ?></span>
                </div>
            </div>
        </div>
    </aside>

    <header class="header">
        <h1><?php echo $__env->yieldContent('page_title'); ?></h1>
    </header>

    <main class="main">
        <?php echo $__env->yieldContent('content'); ?>
    </main>
</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script>
    function openModal(id) {
        document.getElementById(id).style.display = "block";
    }

    function closeModal(id) {
        document.getElementById(id).style.display = "none";
    }

    window.onclick = function (event) {
        document.querySelectorAll(".modal").forEach(modal => {
            if (event.target === modal) {
                modal.style.display = "none";
            }
        });
    }

    <?php if(session('success')): ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '<?php echo e(session('success')); ?>',
            showConfirmButton: false,
            timer: 2000
        });
    <?php elseif(session('error')): ?>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '<?php echo e(session('error')); ?>',
            showConfirmButton: true
        });
    <?php elseif(session('warning')): ?>
        Swal.fire({
            icon: 'warning',
            title: 'Peringatan!',
            text: '<?php echo e(session('warning')); ?>',
            showConfirmButton: true
        });
    <?php endif; ?>

    function confirmDelete(id) {
        Swal.fire({
            title: 'Yakin mau hapus?',
            text: 'Data tidak bisa dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }

    function clearAllFilters() {
    // Reset semua input select2
    $('.select2').val(null).trigger('change');

    // Kosongkan search input
    document.getElementById('searchInput').value = '';

    // Submit form setelah semua direset
    document.getElementById('filterForm').submit();
}


    $(document).ready(function() {
    // Inisialisasi Select2 untuk Kode Perangkat
    $('select[name="region[]"]').select2({
        placeholder: "Pilih Region",
        allowClear: true
    });

    // Inisialisasi Select2 untuk Brand
    $('select[name="site[]"]').select2({
        placeholder: "Pilih Site",
        allowClear: true
    });
    // Inisialisasi Select2 untuk Kode Perangkat
    $('select[name="kode_perangkat[]"]').select2({
        placeholder: "Pilih Perangkat",
        allowClear: true
    });

    // Inisialisasi Select2 untuk Brand
    $('select[name="brand[]"]').select2({
        placeholder: "Pilih Brand",
        allowClear: true
    });
});


</script>
<?php /**PATH /Users/aurelliaaca/PGN-AMS/resources/views/layouts/app.blade.php ENDPATH**/ ?>