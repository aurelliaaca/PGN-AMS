@extends('layouts.app')
@section('title', 'Rack')
@section('page_title', 'Rack')
@section('content')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/three-dots@0.3.2/dist/three-dots.min.css">

    <div class="main">
        <div class="container">

            @if(auth()->user()->role == '1')
                <button class="btn btn-primary mb-3" style="margin-top: 20px;" onclick="openModal('modalTambahRack')">+ Tambah
                    Rack</button>
                <div class="filter-container" style="margin-top: 20px;">
                    <select id="region-filter" name="region[]" multiple class="select2">
                        @foreach($regions as $region)
                            <option value="{{ $region->kode_region }}">{{ $region->nama_region }}</option>
                        @endforeach
                    </select>

                    <select id="site-filter" name="site[]" multiple class="select2" disabled>
                    </select>

                    <div class="search-bar">
                        <input type="text" id="searchInput" class="custom-select" placeholder="Cari" />
                    </div>
                </div>
            @endif


            <div id="loading-overlay"
                style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; display: none; justify-content: center; align-items: center; z-index: 9999;">
                <div class="dot-spin"></div>
            </div>

            <div id="racks-container">
            </div>
        </div>

        <div id="modalTambahRack" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('modalTambahRack')">&times;</span>
                <h5>Tambah Rack</h5>
                <form action="{{ route('rack.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label>Kode Region</label>
                        <select id="regionSelectTambah" name="kode_region" class="form-control" required>
                            <option value="">Pilih Region</option>
                            @foreach($regions as $region)
                                <option value="{{ $region->kode_region }}">{{ $region->nama_region }}</option>
                            @endforeach
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

                    <button type="submit" class="btn btn-primary">Tambah</button>
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
            function toggleTable(tableId) {
                const table = document.getElementById(tableId);

                if (table.style.display === 'block') {
                    table.style.display = 'none';
                } else {
                    table.style.display = 'block';
                }
            }

            let pieCharts = {};

            function createPieChart(elementId, filledU, emptyU) {
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
                                '#181D5C',
                                '#32398E'
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
            
            function loadRacks() {
                const loadingOverlay = document.getElementById('loading-overlay');
                loadingOverlay.style.display = 'flex';

                const selectedRegions = $('#region-filter').length ? $('#region-filter').val() || [] : [];
                const selectedSites = $('#site-filter').length ? $('#site-filter').val() || [] : [];
                const searchKeyword = $('#searchInput').length ? $('#searchInput').val().toLowerCase() : '';

                const racksContainer = document.getElementById('racks-container');

                fetch('{{ route("load.racks") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        regions: selectedRegions,
                        sites: selectedSites,
                        search: searchKeyword
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Received racks:', data.racks);
                        loadingOverlay.style.display = 'none';

                        if (data.error) {
                            racksContainer.innerHTML = `
                            <div class="error-message" style="text-align: center; padding: 20px;">
                                <i class="fas fa-exclamation-triangle" style="color: #ff6b6b; font-size: 24px;"></i>
                                <p style="color: #ff6b6b; margin-top: 10px;">${data.message}</p>
                            </div>
                        `;
                            return;
                        }

                        racksContainer.innerHTML = data.html;

                        @if(auth()->user()->role == '1' || auth()->user()->role == '2')
                            data.racks.forEach(rack => {
                                const chartId = `pieChart-${rack.kode_region}-${rack.kode_site}-${rack.no_rack}`;
                                const canvas = document.getElementById(chartId);
                                if (canvas) {
                                    createPieChart(chartId, rack.filled_u, rack.empty_u);
                                } else {
                                    console.error(`Canvas with ID ${chartId} not found`);
                                }
                            });
                        @endif
                            })
                    .catch(error => {
                        console.error('Error loading rack data:', error);
                        loadingOverlay.style.display = 'none';

                        racksContainer.innerHTML = `
                        <div class="error-message" style="text-align: center; padding: 20px;">
                            <i class="fas fa-exclamation-triangle" style="color: #ff6b6b; font-size: 24px;"></i>
                            <p style="color: #ff6b6b; margin-top: 10px;">Failed to load rack data. Please try again later.</p>
                            <p style="color: #666; font-size: 0.9em; margin-top: 5px;">Error details: ${error.message}</p>
                        </div>
                    `;
                    });
            }

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
                        fetch('{{ route("rack.destroy") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
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
                        fetch('{{ route("rack.destroydata") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
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
                                        loadRacks();
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

            $(document).ready(function () {
                $('#region-filter').select2({
                    placeholder: "Pilih Region",
                    allowClear: true
                });

                $('#site-filter').select2({
                    placeholder: "Pilih Site",
                    allowClear: true
                });

                $('#region-filter').on('change', function () {
                    const selectedRegions = $(this).val();
                    $('#site-filter').prop('disabled', true).empty().append('<option value="">Pilih Site</option>');

                    if (selectedRegions && selectedRegions.length > 0) {
                        $.get('/get-sites', { regions: selectedRegions }, function (data) {
                            $('#site-filter').prop('disabled', false);
                            $.each(data, function (key, value) {
                                $('#site-filter').append(new Option(value, key));
                            });
                        });
                    } else {
                        $.get('/get-sites', function (data) {
                            $('#site-filter').prop('disabled', false);
                            $.each(data, function (key, value) {
                                $('#site-filter').append(new Option(value, key));
                            });
                        });
                    }
                    loadRacks();
                });

                document.getElementById('regionSelectTambah').addEventListener('change', function () {
                    const regionId = this.value;
                    const siteSelect = document.getElementById('siteSelectTambah');

                    siteSelect.innerHTML = '<option value="">Pilih Site</option>';
                    siteSelect.disabled = true;

                    if (regionId) {
                        siteSelect.disabled = false;
                        const sites = @json($sites);
                        const filteredSites = sites.filter(site => site.kode_region == regionId);

                        filteredSites.forEach(site => {
                            const option = document.createElement('option');
                            option.value = site.kode_site;
                            option.textContent = site.nama_site;
                            siteSelect.appendChild(option);
                        });
                    }
                });

                $('#site-filter').on('change', function () {
                    loadRacks();
                });

                let searchTimeout;
                $('#searchInput').on('keyup', function () {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        loadRacks();
                    }, 300);
                });

                loadRacks();

                // Close modal when clicking outside
                window.onclick = function (event) {
                    if (event.target.className === 'modal') {
                        event.target.style.display = 'none';
                    }
                }
            });
        </script>
@endsection