<?php $__env->startSection('title', 'Rack'); ?> 
<?php $__env->startSection('page_title', 'Rack'); ?> 
<?php $__env->startSection('content'); ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/three-dots@0.3.2/dist/three-dots.min.css">

<div class="main">
    <div class="container">

    <button class="btn btn-primary mb-3" onclick="openModal('modalTambahRack')">+ Tambah Rack</button>

            <div class="filter-container">
                <select id="region-filter" name="region[]" multiple class="select2">
                    <?php $__currentLoopData = $regions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $region): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($region->kode_region); ?>"><?php echo e($region->nama_region); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>

                <select id="site-filter" name="site[]" multiple class="select2" disabled>
                </select>

                <div class="search-bar">
                    <input type="text" id="searchInput" class="custom-select" placeholder="Cari" />
                </div>
            </div>

        <div id="loading-overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; display: none; justify-content: center; align-items: center; z-index: 9999;">
            <div class="dot-spin"></div>
        </div>

        <div id="racks-container">
        </div>
    </div>

    <div id="modalTambahRack" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('modalTambahRack')">&times;</span>
        <h5>Tambah Rack</h5>
        <form action="<?php echo e(route('rack.store')); ?>" method="POST">
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

<script>
// Store charts to destroy them when reloading data
let pieCharts = {};

// Function to toggle table visibility
function toggleTable(tableId) {
    const table = document.getElementById(tableId);
    
    if (table.style.display === 'block') {
        table.style.display = 'none';
    } else {
        table.style.display = 'block';
    }
}

// Function to create pie chart
function createPieChart(elementId, filledU, emptyU) {
    // Destroy existing chart if it exists
    if (pieCharts[elementId]) {
        pieCharts[elementId].destroy();
    }
    
    const ctx = document.getElementById(elementId).getContext('2d');
    pieCharts[elementId] = new Chart(ctx, {
        type: 'pie',
        plugins: [ChartDataLabels],
        data: {
            labels: ['Terisi', 'Tersedia'],
            datasets: [{
                data: [filledU, emptyU],
                backgroundColor: [
                    '#181D5C',  // Warna untuk Terisi
                    '#32398E'   // Warna untuk Tersedia
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    enabled: false
                },
                datalabels: {
                    formatter: (value, ctx) => {
                        const label = ctx.chart.data.labels[ctx.dataIndex];
                        const total = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                        
                        if (value === 0) return '';
                        
                        if (value === total) {
                            return `${label}\n${value}U`;
                        }
                        
                        return `${label}\n${value}U`;
                    },
                    color: '#fff',
                    font: {
                        size: 11,
                        weight: 'bold'
                    },
                    textAlign: 'center',
                    padding: 6
                }
            },
            events: []
        }
    });
    
    return pieCharts[elementId];
}

// Function to load rack data
function loadRacks() {
    const loadingOverlay = document.getElementById('loading-overlay');
    loadingOverlay.style.display = 'flex'; // Tampilkan overlay loading

    const selectedRegions = $('#region-filter').val() || [];
    const selectedSites = $('#site-filter').val() || [];
    const searchKeyword = $('#searchInput').val().toLowerCase();
    const racksContainer = document.getElementById('racks-container');

    fetch('<?php echo e(route("load.racks")); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        body: JSON.stringify({
            regions: selectedRegions,
            sites: selectedSites,
            search: searchKeyword
        })
    })
    .then(response => response.json())
    .then(data => {
        // Generate HTML for racks
        let racksHtml = '';
        const racksContainer = document.getElementById('racks-container');
        
        // Filter racks based on selected regions and sites
        const filteredRacks = data.racks.filter(rack => {
            const regionMatch = selectedRegions.length === 0 || selectedRegions.includes(rack.kode_region);
            const siteMatch = selectedSites.length === 0 || selectedSites.includes(rack.kode_site);
            const searchMatch = !searchKeyword || 
                rack.no_rack.toString().toLowerCase().includes(searchKeyword) ||
                rack.site.nama_site.toLowerCase().includes(searchKeyword) ||
                rack.region.nama_region.toLowerCase().includes(searchKeyword);
            
            return regionMatch && siteMatch && searchMatch;
        });

        racksHtml += `
            <div class="card-grid" style="margin-top: 20px;">
        `;
        
        // Generate HTML for each rack
        filteredRacks.forEach(rack => {
            const chartId = `pieChart-${rack.kode_region}-${rack.kode_site}-${rack.no_rack}`;
            const tableId = `table-${rack.kode_region}-${rack.kode_site}-${rack.no_rack}`;
            
            racksHtml += `
                <div class="toggle">
                    <div class="card-item primary clickable" onclick="toggleTable('${tableId}')">                        <div class="icon-wrapper-chart">
                            <canvas id="${chartId}" style="width: 150px; height: 150px;"></canvas>
                        </div>
                        <div class="card-content">
                            <h4>Rack ${rack.no_rack}</h4>
                            <p>${rack.site.nama_site}, ${rack.region.nama_region}</p>
                            <p>Jumlah Perangkat: ${rack.device_count} | Jumlah Fasilitas: ${rack.facility_count}</p>
                            <div class="action-buttons">
                                <button class="btn btn-delete" onclick="confirmDeleteRack('${rack.kode_region}', '${rack.kode_site}', '${rack.no_rack}')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div id="${tableId}" class="tables-container">
                        <div class="table table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID U</th>
                                        <th>ID Perangkat/Fasilitas</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
            `;
            
            // Generate table rows for rack details
            rack.details.forEach(detail => {
                let deviceInfo = 'IDLE';
                
                if (detail.listperangkat) {
                    let deviceCode = [
                        detail.listperangkat.kode_region,
                        detail.listperangkat.kode_site,
                        detail.listperangkat.no_rack,
                        detail.listperangkat.kode_perangkat,
                        detail.listperangkat.perangkat_ke,
                        detail.listperangkat.kode_brand,
                        detail.listperangkat.type
                    ].filter(Boolean).join('-');
                    
                    deviceInfo = deviceCode;
                } else if (detail.listfasilitas) {
                    deviceInfo = detail.listfasilitas.nama_fasilitas;
                }
                
                racksHtml += `
                    <tr>
                        <td>${detail.u}</td>
                        <td>${deviceInfo}</td>
                        <td>
                            <button class="btn btn-delete" onclick="confirmDeleteU('${rack.kode_region}', '${rack.kode_site}', '${rack.no_rack}', '${detail.u}')">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
            
            racksHtml += `
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            `;
        });
        
        racksHtml += `
            </div>
        `;
        
        racksContainer.innerHTML = racksHtml;
        
        // Reinitialize charts
        data.racks.forEach(rack => {
            const chartId = `pieChart-${rack.kode_region}-${rack.kode_site}-${rack.no_rack}`;
            setTimeout(() => {
                createPieChart(chartId, rack.filled_u, rack.empty_u);
            }, 0);
        });
        
        loadingOverlay.style.display = 'none'; // Hentikan loading overlay setelah data selesai dimuat
    })
    .catch(error => {
        console.error('Error loading rack data:', error);
        loadingOverlay.style.display = 'none';
        
        racksContainer.innerHTML = `
            <div class="error-message" style="text-align: center; padding: 20px;">
                <i class="fas fa-exclamation-triangle" style="color: #ff6b6b; font-size: 24px;"></i>
                <p style="color: #ff6b6b; margin-top: 10px;">Failed to load rack data. Please try again later.</p>
            </div>
        `;
    });
}

// ... existing code ...
function confirmDeleteRack(kode_region, kode_site, no_rack) {
    Swal.fire({
        title: 'Hapus Rack?',
        text: 'Pastikan semua U dalam rack ini kosong (tidak ada perangkat atau fasilitas).',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('<?php echo e(route("rack.destroydata")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                body: JSON.stringify({
                    kode_region: kode_region,
                    kode_site: kode_site,
                    no_rack: no_rack
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Rack berhasil dihapus',
                        icon: 'success',
                        confirmButtonColor: '#4f52ba'
                    }).then(() => {
                        loadRacks(); // Reload the racks
                    });
                } else {
                    Swal.fire({
                        title: 'Gagal!',
                        text: data.message || 'Gagal menghapus rack. Pastikan semua U dalam rack kosong.',
                        icon: 'error',
                        confirmButtonColor: '#dc3545'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat menghapus rack',
                    icon: 'error',
                    confirmButtonColor: '#dc3545'
                });
            });
        }
    });
}
// ... existing code ...
// Add this function to your script section
function confirmDeleteU(kode_region, kode_site, no_rack, u) {
    Swal.fire({
        title: 'Hapus Perangkat/Fasilitas?',
        text: 'Apakah Anda yakin ingin menghapus perangkat/fasilitas dari U ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('<?php echo e(route("rack.destroydata")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                body: JSON.stringify({
                    kode_region: kode_region,
                    kode_site: kode_site,
                    no_rack: no_rack,
                    u: u
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#4f52ba'
                    }).then(() => {
                        loadRacks(); // Reload the racks
                    });
                } else {
                    Swal.fire({
                        title: 'Gagal!',
                        text: data.message || 'Gagal menghapus perangkat/fasilitas',
                        icon: 'error',
                        confirmButtonColor: '#dc3545'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat menghapus perangkat/fasilitas',
                    icon: 'error',
                    confirmButtonColor: '#dc3545'
                });
            });
        }
    });
}

$(document).ready(function() {
    // Initialize Select2
    $('#region-filter').select2({
        placeholder: "Pilih Region",
        allowClear: true
    });

    $('#site-filter').select2({
        placeholder: "Pilih Site",
        allowClear: true
    });

    // Handle region filter change
    $('#region-filter').on('change', function() {
        const selectedRegions = $(this).val();
        $('#site-filter').prop('disabled', true).empty().append('<option value="">Pilih Site</option>');

        if (selectedRegions && selectedRegions.length > 0) {
            $.get('/get-sites', { regions: selectedRegions }, function(data) {
                $('#site-filter').prop('disabled', false);
                $.each(data, function(key, value) {
                    $('#site-filter').append(new Option(value, key));
                });
            });
        } else {
            // If no regions selected, load all sites
            $.get('/get-sites', function(data) {
                $('#site-filter').prop('disabled', false);
                $.each(data, function(key, value) {
                    $('#site-filter').append(new Option(value, key));
                });
            });
        }
        loadRacks();
    });

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

    // Handle site filter change
    $('#site-filter').on('change', function() {
        loadRacks();
    });

    // Handle search input with debounce
    let searchTimeout;
    $('#searchInput').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            loadRacks();
        }, 300);
    });

    // Initial load
    loadRacks();
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/aurelliaaca/PGN-AMS/resources/views/menu/rack.blade.php ENDPATH**/ ?>