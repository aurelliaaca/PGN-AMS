<?php $__env->startSection('title', 'Verifikasi Dokumen'); ?>
<?php $__env->startSection('page_title', 'Verifikasi Dokumen'); ?>
<?php $__env->startSection('content'); ?>
<div class="main">
<div class="container">

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama User</th>
                    <th>Nama Dokumen</th>
                    <th>Tanggal Upload</th>
                    <th>Status</th>
                    <th>Masa Berlaku</th>
                    <th>File</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $dokumen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($index + 1); ?></td>
                    <td><?php echo e($d->user->name); ?></td>
                    <td><?php echo e($d->nama_dokumen); ?></td>
                    <td><?php echo e($d->created_at->format('d/m/Y H:i')); ?></td>
                    <td>
                        <span style="display: inline-flex; align-items: center;">
                            <span style="width: 10px; height: 10px; border-radius: 3px; margin-right: 8px;
                                background-color: 
                                    <?php echo e($d->status == 'pending' ? '#ffc107' : 
                                    ($d->status == 'diterima' ? '#28a745' : '#dc3545')); ?>;">
                            </span>
                            <?php echo e(ucfirst($d->status)); ?>

                        </span>
                    </td>
                    <td><?php echo e($d->masa_berlaku ? $d->masa_berlaku->translatedFormat('d F Y H:i') : ''); ?></td>
                    <td>
                        <a href="<?php echo e(asset('storage/' . $d->file_path)); ?>" target="_blank" class="btn btn-sm btn-info">Lihat</a>
                        <?php if($d->signature): ?>
                            <br>
                            <small class="text-muted">Ditandatangani oleh: <?php echo e($d->signed_by); ?></small><br>
                            <small class="text-muted">Pada: <?php echo e($d->signed_at->format('d/m/Y H:i')); ?></small>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="action-buttons">
                        <?php if($d->status == 'pending'): ?>
                            <form action="<?php echo e(route('verifikasi.approve', $d->id)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-sm btn-primary" onclick="return confirm('Apakah Anda yakin ingin menerima dokumen ini?')">
                                    <i class="fas fa-check"></i> Terima
                                </button>
                            </form>
                            <form action="<?php echo e(route('verifikasi.reject', $d->id)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-sm btn-delete" onclick="return confirm('Apakah Anda yakin ingin menolak dokumen ini?')">
                                    <i class="fas fa-times"></i> Tolak
                                </button>
                            </form>
                        <?php else: ?>
                            <span class="text-muted">Sudah diverifikasi</span>
                        <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" class="text-center">Tidak ada dokumen yang perlu diverifikasi</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</div>

<!-- Modal Tanda Tangan -->
<div class="modal fade" id="signatureModal" tabindex="-1" role="dialog" aria-labelledby="signatureModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="signatureModalLabel">Tanda Tangan Digital</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="signatureForm" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="dokumen_id" id="dokumen_id">
                    <div class="form-group">
                        <label>Tanda Tangan:</label>
                        <div class="border rounded p-2">
                            <canvas id="signaturePad" width="400" height="200" style="border:1px solid #000000;"></canvas>
                        </div>
                        <div class="mt-2">
                            <button type="button" class="btn btn-sm btn-secondary" onclick="clearSignature()">Clear</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Status Verifikasi:</label>
                        <select name="status" class="form-control" required>
                            <option value="diterima">Terima</option>
                            <option value="ditolak">Tolak</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="saveSignature()">Simpan</button>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
    let signaturePad;
    let currentDokumenId;

    function openSignatureModal(dokumenId) {
        currentDokumenId = dokumenId;
        document.getElementById('dokumen_id').value = dokumenId;
        
        const canvas = document.getElementById('signaturePad');
        signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgb(255, 255, 255)'
        });
        
        $('#signatureModal').modal('show');
    }

    function clearSignature() {
        signaturePad.clear();
    }

    function saveSignature() {
        if (signaturePad.isEmpty()) {
            alert('Harap tanda tangan terlebih dahulu');
            return;
        }

        const signatureData = signaturePad.toDataURL();
        const form = document.getElementById('signatureForm');
        const formData = new FormData(form);
        formData.append('signature', signatureData);

        fetch(`/verifikasi/sign/${currentDokumenId}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert('Terjadi kesalahan saat menyimpan tanda tangan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menyimpan tanda tangan');
        });
    }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/aurelliaaca/PGN-AMS/resources/views/VMS/admin/verifikasi.blade.php ENDPATH**/ ?>