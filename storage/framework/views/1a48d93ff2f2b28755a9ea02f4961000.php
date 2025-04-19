<?php $__env->startSection('title', 'Aset Perangkat'); ?>
<?php $__env->startSection('page_title', 'Aset Perangkat'); ?>

<?php $__env->startSection('content'); ?>
    <div class="main">
        <div class="button-wrapper">
            <button class="btn btn-primary mb-3" onclick="openModal('modalTambahPerangkat')">+ Tambah Perangkat</button>
            <button type="button" class="btn btn-primary" onclick="openModal('importModal')">Impor Data Perangkat</button>

            <div id="importModal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal('importModal')">&times;</span>
                    <h5>Impor Data Perangkat</h5>
                    <form action="<?php echo e(route('import.perangkat')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="form-group">
                        <label for="file">Pilih File (XLSX, XLS, CSV)</label>
                        <input type="file" class="form-control" name="file" accept=".xlsx,.xls,.csv" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Impor Data</button>
                    </form>
                </div>
            </div>

            <button class="btn btn-primary mb-3">
                <a href="<?php echo e(url('export/perangkat')); ?>" style="color: white; text-decoration: none;">
                    Ekspor Data Perangkat
                </a>
            </button>

            <button class="btn btn-clear mb-3" onclick="clearAllFilters()">Clear All Filter</button>
        </div>

        <div class="filter">
            <form method="GET" id="filterForm" action="<?php echo e(route('perangkat.index')); ?>">
                <div class="filter-container">
                    <div class="select-group">
                        <select name="region[]" class="select2" multiple onchange="document.getElementById('filterForm').submit()">
                            <option value="" disabled>Pilih Region</option>
                            <?php $__currentLoopData = $regions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $region): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($region->kode_region); ?>" <?php echo e(in_array($region->kode_region, (array) request('region')) ? 'selected' : ''); ?>>
                                    <?php echo e($region->nama_region); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>

                        <select name="site[]" class="select2" multiple onchange="document.getElementById('filterForm').submit()">
                            <option value="" disabled>Pilih Site</option>
                            <?php $__currentLoopData = $sites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $site): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($site->kode_site); ?>" <?php echo e(in_array($site->kode_site, (array) request('site')) ? 'selected' : ''); ?>>
                                    <?php echo e($site->nama_site); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>

                        <select name="kode_perangkat[]" class="select2" multiple onchange="document.getElementById('filterForm').submit()">
                            <option value="" disabled>Pilih Perangkat</option>
                            <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kode): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($kode->kode_perangkat); ?>" <?php echo e(in_array($kode->kode_perangkat, (array) request('kode_perangkat')) ? 'selected' : ''); ?>>
                                    <?php echo e($kode->nama_perangkat); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>

                        <select name="brand[]" class="select2" multiple onchange="document.getElementById('filterForm').submit()">
                            <option value="" disabled>Pilih Brand</option>
                            <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($brand->kode_brand); ?>" <?php echo e(in_array($brand->kode_brand, (array) request('brand')) ? 'selected' : ''); ?>>
                                    <?php echo e($brand->nama_brand); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="search-bar">
                        <form action="" method="GET" id="filterForm" style="display: flex; align-items: center; gap: 8px;">
                            <input type="text" name="search" placeholder="Cari..." value="<?php echo e(request('search')); ?>" id="searchInput">
                            <button type="submit" class="btn btn-search">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive <?php echo e(Route::currentRouteName() == 'perangkat.index' ? 'table-responsive-aset' : ''); ?>">
            <table class="table">
                <thead>
                    <tr>
                    <th class="col-status"></th>
                    <th class="col-no">No</th>
                    <th class="col-region">Region</th>
                    <th class="col-site">Site</th>
                    <th class="col-rack">No Rack</th>
                    <th class="col-nama">Perangkat</th>
                    <th class="col-brand">Brand</th>
                    <th class="col-type">Type</th>
                    <th class="col-actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $dataperangkat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $perangkat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <div class="status-box <?php echo e($perangkat->no_rack ? 'bg-success' : 'bg-danger'); ?>"></div>
                            </td>
                            <td><?php echo e($loop->iteration); ?></td>
                            <td><?php echo e($perangkat->region->nama_region); ?></td>
                            <td><?php echo e($perangkat->site->nama_site); ?></td>
                            <td><?php echo e($perangkat->no_rack); ?></td>
                            <td><?php echo e($perangkat->jenisperangkat->nama_perangkat); ?></td>
                            <td><?php echo e(optional($perangkat->brandperangkat)->nama_brand); ?></td>
                            <td><?php echo e($perangkat->type); ?></td>
                            <td>
                                <button class="btn btn-eye"
                                    onclick="openModal('modalViewPerangkat<?php echo e($perangkat->id_perangkat); ?>')">
                                    <i class="fas fa-eye"></i>
                                </button>   
                                <button class="btn btn-edit"
                                    onclick="openModal('modalEditPerangkat<?php echo e($perangkat->id_perangkat); ?>')">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-delete" onclick="confirmDelete(<?php echo e($perangkat->id_perangkat); ?>)">
                                    <i class="fas fa-trash-alt"></i>
                                </button>

                                <form id="delete-form-<?php echo e($perangkat->id_perangkat); ?>" 
                                    action="<?php echo e(route('perangkat.destroy', $perangkat->id_perangkat)); ?>" 
                                    method="POST" style="display: none;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                </form>
                            </td>
                        </tr>

                        <!-- Modal View -->
                        <div id="modalViewPerangkat<?php echo e($perangkat->id_perangkat); ?>" class="modal">
                            <div class="modal-content">
                                <span class="close" onclick="closeModal('modalViewPerangkat<?php echo e($perangkat->id_perangkat); ?>')">&times;</span>
                                <h5>Detail Perangkat</h5>
                                
                                <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                                    <!-- Kolom kiri -->
                                    <div style="width: 48%;">
                                        <label>Region</label>
                                        <input type="text" value="<?php echo e($perangkat->region->nama_region); ?>" readonly class="form-control">

                                        <label>Site</label>
                                        <input type="text" value="<?php echo e($perangkat->site->nama_site); ?>" readonly class="form-control">

                                        <label>No Rack</label>
                                        <input type="text" value="<?php echo e($perangkat->no_rack); ?>" readonly class="form-control">

                                        <label>Jenis Perangkat</label>
                                        <input type="text" value="<?php echo e($perangkat->jenisperangkat->nama_perangkat); ?>" readonly class="form-control">
                                    </div>

                                    <!-- Kolom kanan -->
                                    <div style="width: 48%;">
                                        <label>Perangkat ke-</label>
                                        <input type="text" value="<?php echo e($perangkat->perangkat_ke); ?>" readonly class="form-control">

                                        <label>Brand</label>
                                        <input type="text" value="<?php echo e(optional($perangkat->brandperangkat)->nama_brand); ?>" readonly class="form-control">

                                        <label>Tipe</label>
                                        <input type="text" value="<?php echo e($perangkat->type); ?>" readonly class="form-control">

                                        <label>U Awal - U Akhir</label>
                                        <input type="text" value="<?php echo e($perangkat->uawal); ?> - <?php echo e($perangkat->uakhir); ?>" readonly class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>


                        
                        <div id="modalEditPerangkat<?php echo e($perangkat->id_perangkat); ?>" class="modal">
                            <div class="modal-content">
                                <span class="close"
                                    onclick="closeModal('modalEditPerangkat<?php echo e($perangkat->id_perangkat); ?>')">&times;</span>
                                <h5>Edit Perangkat</h5>
                                <form action="<?php echo e(route('perangkat.update', $perangkat->id_perangkat)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PUT'); ?>
                                    <div class="mb-3">
                                        <label>Kode Region</label>
                                        <select name="kode_region" class="form-control regionSelectEdit"
                                            data-id="<?php echo e($perangkat->id_perangkat); ?>" required>
                                            <option value="">Pilih Region</option>
                                            <?php $__currentLoopData = $regions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $region): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($region->kode_region); ?>" <?php echo e($perangkat->kode_region == $region->kode_region ? 'selected' : ''); ?>>
                                                    <?php echo e($region->nama_region); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label>Kode Site</label>
                                        <select name="kode_site" class="form-control siteSelectEdit"
                                            data-id="<?php echo e($perangkat->id_perangkat); ?>" required>
                                            <option value="">Pilih Site</option>
                                            <?php $__currentLoopData = $sites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $site): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($site->kode_region == $perangkat->kode_region): ?>
                                                    <option value="<?php echo e($site->kode_site); ?>" <?php echo e($perangkat->kode_site == $site->kode_site ? 'selected' : ''); ?>>
                                                        <?php echo e($site->nama_site); ?>

                                                    </option>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label>No Rack</label>
                                        <input type="text" name="no_rack" class="form-control"
                                            value="<?php echo e($perangkat->no_rack ?? ''); ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label>Kode Perangkat</label>
                                        <select name="kode_perangkat" class="form-control" required>
                                            <option value="">Pilih Kode Perangkat</option>
                                            <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jenisperangkat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($jenisperangkat->kode_perangkat); ?>" 
                                                    <?php echo e($perangkat->kode_perangkat == $jenisperangkat->kode_perangkat ? 'selected' : ''); ?>><?php echo e($jenisperangkat->nama_perangkat); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>                   
                                    <div class="mb-3">
                                        <label>Kode Brand</label>
                                        <select name="kode_brand" class="form-control">
                                            <option value="">Pilih Kode Brand</option>
                                            <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brandperangkat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($brandperangkat->kode_brand); ?>" 
                                                    <?php echo e($perangkat->kode_brand == $brandperangkat->kode_brand ? 'selected' : ''); ?>>
                                                    <?php echo e($brandperangkat->nama_brand); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label>Type</label>
                                        <input type="text" name="type" class="form-control" value="<?php echo e($perangkat->type ?? ''); ?>"
                                            >
                                    </div>
                                    <div class="mb-3">
                                        <label>U Awal</label>
                                        <input type="number" name="uawal" class="form-control"
                                            value="<?php echo e($perangkat->uawal ?? ''); ?>" >
                                    </div>
                                    <div class="mb-3">
                                        <label>U Akhir</label>
                                        <input type="number" name="uakhir" class="form-control"
                                            value="<?php echo e($perangkat->uakhir ?? ''); ?>" >
                                    </div>
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        
        <div id="modalTambahPerangkat" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('modalTambahPerangkat')">&times;</span>
                <h5>Tambah Perangkat</h5>
                <form action="<?php echo e(route('perangkat.store')); ?>" method="POST" id="formTambahPerangkat">
                    <?php echo csrf_field(); ?>
        <div class="mb-3">
            <label>Kode Region</label>
            <select id="regionSelectTambah" name="kode_region" class="form-control" required>
                <option value="">Pilih Region</option>
                <?php $__currentLoopData = $regions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $region): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($region->kode_region); ?>"><?php echo e($region->nama_region); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Kode Site</label>
            <select id="siteSelectTambah" name="kode_site" class="form-control" required disabled>
                <option value="">Pilih Site</option>
            </select>
        </div>

            <div class="mb-3">
                <label>No Rack</label>
                <input type="text" name="no_rack" class="form-control" id="no_rack" value="">
            </div>

            <div class="mb-3">
                <label>Kode Perangkat</label>
                <select name="kode_perangkat" class="form-control" required>
                    <option value="">Pilih Kode Perangkat</option>
                    <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jenisperangkat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($jenisperangkat->kode_perangkat); ?>">
                            <?php echo e($jenisperangkat->nama_perangkat); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="mb-3">
                <label>Kode Brand</label>
                <select name="kode_brand" class="form-control" >
                    <option value="">Pilih Kode Brand</option>
                    <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brandperangkat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($brandperangkat->kode_brand); ?>">
                            <?php echo e($brandperangkat->nama_brand); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="mb-3">
                <label>Type</label>
                <input type="text" name="type" class="form-control" value="">
            </div>

            <div class="mb-3">
                <label>U Awal</label>
                <input type="number" name="uawal" class="form-control" value="" id="uawal">
            </div>

            <div class="mb-3">
                <label>U Akhir</label>
                <input type="number" name="uakhir" class="form-control" value="" id="uakhir">
            </div>

            <button type="submit" class="btn btn-primary">Tambah</button>
        </form>
    </div>
</div>


    </div>
    <script>
        // Menangani perubahan pada Region
        document.getElementById('regionSelectTambah').addEventListener('change', function() {
            const regionId = this.value;
            const siteSelect = document.getElementById('siteSelectTambah');

            // Reset dan nonaktifkan site select
            siteSelect.innerHTML = '<option value="">Pilih Site</option>';
            siteSelect.disabled = true;

            // Jika Region dipilih, aktifkan site select dan filter site berdasarkan region
            if (regionId) {
                siteSelect.disabled = false;
                const sites = <?php echo json_encode($sites, 15, 512) ?>;
                const filteredSites = sites.filter(site => site.kode_region == regionId);

                filteredSites.forEach(site => {
                    const option = document.createElement('option');
                    option.value = site.kode_site;
                    option.textContent = site.nama_site;
                    siteSelect.appendChild(option);
                });
            }
        });

        // Menangani perubahan pada input no_rack
        document.getElementById('no_rack').addEventListener('input', function () {
            const noRack = this.value;
            const uawalField = document.getElementById('uawal');
            const uakhirField = document.getElementById('uakhir');

            // Jika no_rack diisi, uawal dan uakhir menjadi required
            if (noRack) {
                uawalField.setAttribute('required', 'required');
                uakhirField.setAttribute('required', 'required');
            } else {
                uawalField.removeAttribute('required');
                uakhirField.removeAttribute('required');
            }
        });

        // Validasi form sebelum submit
        document.getElementById('formTambahPerangkat').addEventListener('submit', function (event) {
            const uawal = parseFloat(document.getElementById('uawal').value);
            const uakhir = parseFloat(document.getElementById('uakhir').value);

            // Pastikan uawal < uakhir dan tidak bernilai negatif
            if (uawal >= uakhir) {
                alert('U Awal harus lebih kecil dari U Akhir.');
                event.preventDefault(); // Cegah form untuk dikirim
            }

            if (uawal < 0 || uakhir < 0) {
                alert('U Awal dan U Akhir tidak boleh bernilai negatif.');
                event.preventDefault(); // Cegah form untuk dikirim
            }
        });

        // Menangani perubahan pada Region di form edit
        document.querySelectorAll('.regionSelectEdit').forEach(select => {
            select.addEventListener('change', function() {
                const regionId = this.value;
                const perangkatId = this.getAttribute('data-id');
                const siteSelect = document.querySelector(`.siteSelectEdit[data-id="${perangkatId}"]`);

                // Reset dan nonaktifkan site select
                siteSelect.innerHTML = '<option value="">Pilih Site</option>';
                siteSelect.disabled = true;

                // Jika Region dipilih, aktifkan site select dan filter site berdasarkan region
                if (regionId) {
                    siteSelect.disabled = false;
                    const sites = <?php echo json_encode($sites, 15, 512) ?>;
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

        // Validasi form edit sebelum submit
        document.querySelectorAll('form[action*="perangkat/update"]').forEach(form => {
            form.addEventListener('submit', function(event) {
                const uawal = parseFloat(this.querySelector('input[name="uawal"]').value);
                const uakhir = parseFloat(this.querySelector('input[name="uakhir"]').value);
                const noRack = this.querySelector('input[name="no_rack"]').value;

                // Jika no_rack diisi, pastikan uawal dan uakhir juga diisi
                if (noRack && (!uawal || !uakhir)) {
                    alert('U Awal dan U Akhir wajib diisi jika No Rack diisi.');
                    event.preventDefault();
                    return;
                }

                // Pastikan uawal < uakhir dan tidak bernilai negatif
                if (uawal >= uakhir) {
                    alert('U Awal harus lebih kecil dari U Akhir.');
                    event.preventDefault();
                }

                if (uawal < 0 || uakhir < 0) {
                    alert('U Awal dan U Akhir tidak boleh bernilai negatif.');
                    event.preventDefault();
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/aurelliaaca/PGN-AMS/resources/views/aset/perangkat.blade.php ENDPATH**/ ?>