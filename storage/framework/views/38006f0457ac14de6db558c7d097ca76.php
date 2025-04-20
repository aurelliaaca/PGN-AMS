<?php $__env->startSection('title', 'Dashboard'); ?> 
<?php $__env->startSection('page_title', 'Dashboard'); ?> 
<?php $__env->startSection('content'); ?>
    <div class="main">
        <div class="card-section">
            <div class="card-item">
                <div class="card-icon"><i class="fa-solid fa-earth-americas"></i></div>
                <div class="card-content">
                    <h4>Region</h4>
                    <p><?php echo e($jumlahRegion); ?> data</p>
                </div>
            </div>
            <div class="card-item">
                <div class="card-icon"><i class="fa-solid fa-building"></i></div>
                <div class="card-content">
                    <h4>POP</h4>
                    <p><?php echo e($jumlahJenisSite['POP'] ?? 0); ?> data</p>
                </div>
            </div>
            <div class="card-item">
                <div class="card-icon"><i class="fa-solid fa-building-user"></i></div>
                <div class="card-content">
                    <h4>POC</h4>
                    <p><?php echo e($jumlahJenisSite['POC'] ?? 0); ?> data</p>
                </div>
            </div>
        </div>
        <div class="card-section">
            <div class="card-item">
                <div class="card-icon"><i class="fas fa-tools"></i></div>
                <div class="card-content">
                    <h4>Perangkat</h4>
                    <p><?php echo e($jumlahPerangkat); ?> data</p>
                </div>
            </div>
            <div class="card-item">
                <div class="card-icon"><i class="fas fa-building"></i></div>
                <div class="card-content">
                    <h4>Fasilitas</h4>
                    <p><?php echo e($jumlahFasilitas); ?> data</p>
                </div>
            </div>
            <div class="card-item">
                <div class="card-icon"><i class="fas fa-ruler"></i></div>
                <div class="card-content">
                    <h4>Alat Ukur</h4>
                    <p><?php echo e($jumlahAlatUkur); ?> data</p>
                </div>
            </div>
            <div class="card-item">
                <div class="card-icon"><i class="fas fa-network-wired"></i></div>
                <div class="card-content">
                    <h4>Jaringan</h4>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/aurelliaaca/PGN-AMS/resources/views/home.blade.php ENDPATH**/ ?>