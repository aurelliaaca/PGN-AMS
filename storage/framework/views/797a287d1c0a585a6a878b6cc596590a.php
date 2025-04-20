<?php $__env->startSection('title', 'Pendaftaran Kunjungan'); ?>
<?php $__env->startSection('page_title', 'Pendaftaran Kunjungan'); ?>
<?php $__env->startSection('content'); ?>
<div class="main">
<div class="container">
    <!-- Tombol untuk membuka modal -->
    <button class="btn btn-primary mb-3" onclick="openModal('modalAjukanDCS')">Ajukan Permohonan Visit Data Center</button>
    <!-- Modal Upload Dokumen -->
        <div class="modal" id="modalAjukanDCS">
            <div class="modal-content">
                <span class="close" onclick="closeModal('modalAjukanDCS')">&times;</span>
                <h5>Ajukan Visit DCS</h5>
                <form action="<?php echo e(route('dokumen.store')); ?>" method="POST" id="formAjukanDCS" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label>Catatan</label>
                        <input type="text" name="catatan" class="form-control" id="catatan" value="">
                    </div>
                    <div class="mb-3">
                        <label>Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control" id="start_date" required>
                        <small class="text-muted">Tanggal akhir akan otomatis 7 hari setelah tanggal mulai</small>
                    </div>
                    <div class="mb-3">
                        <label>Pilih NDA Aktif</label>
                        <select name="verifikasi_nda_id" id="verifikasi_nda_id" class="form-control" required>
                            <option value="">-- Pilih NDA --</option>
                            <?php $__currentLoopData = $activeNdas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nda): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($nda->id); ?>">NDA berlaku sampai <?php echo e($nda->masa_berlaku->format('d F Y')); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php if(count($activeNdas) == 0): ?>
                            <small class="text-danger">Anda tidak memiliki NDA yang aktif. Silahkan ajukan verifikasi NDA terlebih dahulu.</small>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label>File DCAF (PDF atau DOCX)</label>
                        <input type="file" name="dcaf_file" id="dcaf_file" class="form-control" accept=".pdf,.doc,.docx" required>
                        <small class="text-muted">Maksimum ukuran file: 10MB</small>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Kirim</button>
                    </div>
                </form>
            </div>
        </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal Upload</th>
                    <th>Status NDA</th>
                    <th>Masa Berlaku NDA</th>
                    <th>Status DCAF</th>
                    <th>Masa Berlaku DCAF</th>
                    <th>NDA</th>
                    <th>DCAF</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $dcafs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $dcaf): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($index + 1); ?></td>
                    <td><?php echo e($dcaf->created_at->format('d/m/Y H:i')); ?></td>
                    <td>
                        <span style="display: inline-flex; align-items: center;">
                            <span style="width: 10px; height: 10px; border-radius: 3px; margin-right: 8px;
                                background-color: 
                                    <?php echo e($dcaf->nda->status == 'pending' ? '#ffc107' : 
                                    ($dcaf->nda->status == 'diterima' ? '#28a745' : '#dc3545')); ?>;">
                            </span>
                            <?php echo e(ucfirst($dcaf->nda->status)); ?>

                        </span>
                    </td>
                    <td><?php echo e($dcaf->nda->masa_berlaku ? $dcaf->nda->masa_berlaku->format('d/m/Y H:i') : '-'); ?></td>
                    <td>
                        <span style="display: inline-flex; align-items: center;">
                            <span style="width: 10px; height: 10px; border-radius: 3px; margin-right: 8px;
                                background-color: 
                                    <?php echo e($dcaf->status == 'pending' ? '#ffc107' : 
                                    ($dcaf->status == 'diterima' ? '#28a745' : '#dc3545')); ?>;">
                            </span>
                            <?php echo e(ucfirst($dcaf->status)); ?>

                        </span>
                    </td>
                    <td><?php echo e($dcaf->masa_berlaku ? $dcaf->masa_berlaku->format('d/m/Y H:i') : '-'); ?></td>
                    <td>
                        <a href="<?php echo e(asset('storage/' . $dcaf->nda->file_path)); ?>" target="_blank" class="btn btn-sm btn-info">Lihat NDA</a>
                    </td>
                    <td>
                        <a href="<?php echo e(asset('storage/' . $dcaf->file_path)); ?>" target="_blank" class="btn btn-sm btn-info">Lihat DCAF</a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" class="text-center">Tidak ada riwayat permohonan</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/aurelliaaca/PGN-AMS/resources/views/VMS/user/pendaftarankunjungan.blade.php ENDPATH**/ ?>