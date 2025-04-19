<?php $__env->startSection('title', 'Rack'); ?> 
<?php $__env->startSection('page_title', 'Rack'); ?> 
<?php $__env->startSection('content'); ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

<div class="main">
    <button class="btn btn-primary mb-3" onclick="openModal('modalTambahRack')">+ Tambah Rack</button>
    
    <div id="loading-overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; display: flex; justify-content: center; align-items: center; z-index: 9999;">
        <div class="dot-spin"></div>
    </div>
    
    <div class="card-content <?php echo e(Route::currentRouteName() == 'rack.index' ? 'tiga' : ''); ?>" id="racks-container">
        <!-- Racks will be loaded here via AJAX -->
    </div>

    <div id="modalTambahRack" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modalTambahRack')">&times;</span>
            <h5>Tambah Rack</h5>
            <form id="formTambahRack">
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
                    <input type="text" name="no_rack" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Total U</label>
                    <input type="number" name="total_u" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Tambah Rack</button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        loadRacks();
        
        // Form submission handler
        document.getElementById('formTambahRack').addEventListener('submit', function(e) {
            e.preventDefault();
            submitRackForm();
        });
        
        // Region change handler
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
    });
    
    function loadRacks() {
        document.getElementById('loading-overlay').style.display = 'flex';
        
        fetch('<?php echo e(route("rack.getData")); ?>')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderRacks(data.data);
                } else {
                    showError('Gagal memuat data');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('Terjadi kesalahan saat memuat data');
            })
            .finally(() => {
                document.getElementById('loading-overlay').style.display = 'none';
            });
    }
    
    function renderRacks(racksData) {
        const container = document.getElementById('racks-container');
        container.innerHTML = '';
        
        if (racksData.length === 0) {
            container.innerHTML = '<div class="alert alert-info">Tidak ada data rack</div>';
            return;
        }
        
        racksData.forEach((rackData, index) => {
            const rack = rackData.rack;
            const stats = rackData.stats;
            const id = `${rack.kode_region}-${rack.kode_site}-${rack.no_rack}`;
            
            const rackElement = document.createElement('div');
            rackElement.className = 'toggle';
            rackElement.innerHTML = `
                <div class="card-item" onclick="toggleTable(${index})">
                    <div class="card-content">
                        <h4>Rack: ${rack.no_rack}</h4>
                        <p>${rack.site.nama_site}, ${rack.region.nama_region}</p>

                        <div class="action-buttons <?php echo e(Route::currentRouteName() == 'rack.index' ? 'btn-kiri' : 'btn-kanan'); ?>">
                            <button class="btn btn-delete" onclick="event.stopPropagation(); confirmDelete('${id}')">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                    <div class="icon-wrapper-chart">
                        <canvas id="pieChart${index}" class="canvas"></canvas>
                    </div>
                </div>

                <div class="tables-container">
                    <div id="table-${index}" style="display: none;">
                        <div class="table table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>U</th>
                                        <th>Aset</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${renderRackItems(rackData.items)}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            `;
            
            container.appendChild(rackElement);
            
            // Initialize chart
            setTimeout(() => {
                initializeChart(index, stats);
            }, 100);
        });
    }
    
    function renderRackItems(items) {
        return Array.from(items).map(item => {
            const id = `${item.kode_region}-${item.kode_site}-${item.no_rack}-${item.u}`;
            let assetName = 'IDLE';
            
            if (item.listperangkat) {
                assetName = item.listperangkat.jenisperangkat.nama_perangkat;
            } else if (item.listfasilitas) {
                assetName = item.listfasilitas.jenisfasilitas.nama_fasilitas;
            }
            
            return `
                <tr>
                    <td>${item.u}</td>
                    <td>${assetName}</td>
                    <td>
                        <button type="button" class="btn btn-delete mb-3" 
                            onclick="confirmDeleteItem('${id}')">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
            `;
        }).join('');
    }
    
    function initializeChart(index, data) {
        const ctx = document.getElementById(`pieChart${index}`).getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Perangkat', 'Fasilitas', 'IDLE'],
                datasets: [{
                    data: [data.perangkat, data.fasilitas, data.idle],
                    backgroundColor: ['#32398E', '#36A2EB', '#181D5C'],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        enabled: true,
                    },
                    datalabels: {
                        color: '#fff',
                        font: {
                            weight: 'bold',
                            size: 10
                        },
                        formatter: function(value, context) {
                            if (value > 0) {
                                const label = context.chart.data.labels[context.dataIndex];
                                return `${label}: ${value}`;
                            }
                            return '';
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });
    }
    
    function toggleTable(index) {
        const table = document.getElementById('table-' + index);
        table.style.display = table.style.display === 'none' ? 'block' : 'none';
    }
    
    function openModal(modalId) {
        document.getElementById(modalId).style.display = 'block';
    }
    
    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }
    
    function submitRackForm() {
        const form = document.getElementById('formTambahRack');
        const formData = new FormData(form);
        
        document.getElementById('loading-overlay').style.display = 'flex';
        
        fetch('<?php echo e(route("rack.store")); ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message
                });
                form.reset();
                closeModal('modalTambahRack');
                loadRacks();
            } else {
                showError(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Terjadi kesalahan saat menyimpan data.');
        })
        .finally(() => {
            document.getElementById('loading-overlay').style.display = 'none';
        });
    }
    
    function confirmDelete(id) {
        event.stopPropagation();
        
        Swal.fire({
            title: 'Hapus Rack?',
            text: "Rack akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                deleteRack(id);
            }
        });
    }
    
    function deleteRack(id) {
        const [region, site, rack] = id.split('-');
        document.getElementById('loading-overlay').style.display = 'flex';
        
        fetch(`/rack/${region}/${site}/${rack}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message
                });
                loadRacks();
            } else {
                showError(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Terjadi kesalahan saat menghapus data.');
        })
        .finally(() => {
            document.getElementById('loading-overlay').style.display = 'none';
        });
    }
    
    function confirmDeleteItem(id) {
        event.stopPropagation();
        
        Swal.fire({
            title: 'Hapus Item?',
            text: "Item akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                deleteRackItem(id);
            }
        });
    }
    
    function deleteRackItem(id) {
        const [region, site, rack, u] = id.split('-');
        document.getElementById('loading-overlay').style.display = 'flex';
        
        fetch(`/datarack/${region}/${site}/${rack}/${u}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message
                });
                loadRacks();
            } else {
                showError(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Terjadi kesalahan saat menghapus data.');
        })
        .finally(() => {
            document.getElementById('loading-overlay').style.display = 'none';
        });
    }
    
    function showError(message) {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: message
        });
    }
</script>

<style>
    .dot-spin {
        transform: scale(2);
        animation: dot-spin-animation 1.5s infinite;
    }

    @keyframes dot-spin-animation {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/aurelliaaca/PGN-AMS/resources/views/menu/rack.blade.php ENDPATH**/ ?>