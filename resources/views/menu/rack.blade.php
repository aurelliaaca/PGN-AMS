@extends('layouts.app')
@section('title', 'Rack') 
@section('page_title', 'Rack') 
@section('content')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

<div class="main">
    <button class="btn btn-primary mb-3" onclick="openModal('modalTambahRack')">+ Tambah Rack</button>
    <div class="card-content {{ Route::currentRouteName() == 'rack.index' ? 'tiga' : '' }}">

    <div id="loading-overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; display: none; justify-content: center; align-items: center; z-index: 9999;">
            <div class="dot-spin"></div>
        </div>
    @foreach ($racksUnik as $index => $rack)
            <div class="toggle">
                <div class="card-item" onclick="toggleTable({{ $index }})">
                    <div class="card-content">

                        <h4>Rack: {{ $rack->no_rack }}</h4>
                        <p>{{ $rack->site->nama_site }}, {{ $rack->region->nama_region }}</p>

                        <div class="action-buttons {{ Route::currentRouteName() == 'rack.index' ? 'btn-kiri' : 'btn-kanan' }}">
                            @php
                                $id = $rack->kode_region . '-' . $rack->kode_site . '-' . $rack->no_rack;
                            @endphp

                            <!-- <button class="btn btn-edit"
                                onclick="openModal('modalEditRack{{ $id }}')">
                                <i class="fas fa-edit"></i>
                            </button> -->

                            <button class="btn btn-delete" onclick="confirmDelete('{{ $id }}')">
                                <i class="fas fa-trash-alt"></i>
                            </button>

                            <form id="delete-form-{{ $id }}" 
                                action="{{ route('rack.destroy', [$rack->kode_region, $rack->kode_site, $rack->no_rack]) }}" 
                                method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form> 
                        </div>
                    </div>
                    <div class="icon-wrapper-chart">
                        <canvas id="pieChart{{ $index }}" class="canvas"></canvas>
                    </div>
                </div>

                <div class="tables-container">
                    <div id="table-{{ $index }}" style="display: none;">
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
                                    @foreach ($semuaRack->where('kode_region', $rack->kode_region)
                                                    ->where('kode_site', $rack->kode_site)
                                                    ->where('no_rack', $rack->no_rack) 
                                                    ->sortByDesc('u') as $item)
                                        <tr>
                                            <td>{{ $item->u }}</td>
                                            <td>
                                                @if ($item->listperangkat)
                                                    {{ $item->listperangkat->jenisperangkat->nama_perangkat }}
                                                @elseif ($item->listfasilitas)
                                                    {{ $item->listfasilitas->jenisfasilitas->nama_fasilitas }}
                                                @else
                                                    IDLE
                                                @endif
                                            </td>
                                            @php
                                                $id = $item->kode_region . '-' . $item->kode_site . '-' . $item->no_rack . '-' . $item->u;
                                            @endphp

                                            <td>
                                                <button type="button" class="btn btn-delete mb-3"
                                                    onclick="confirmDelete('{{ $id }}')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>

                                                <form id="delete-form-{{ $id }}"
                                                    action="{{ route('datarack.destroy', [$item->kode_region, $item->kode_site, $item->no_rack, $item->u]) }}" 
                                                    method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
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

            <button type="submit" class="btn btn-primary">Tambah Rack</button>
        </form>
    </div>
</div>

</div>
<script>
    function toggleTable(index) {
        const table = document.getElementById('table-' + index);
        table.style.display = table.style.display === 'none' ? 'block' : 'none';
    }

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

    // Initialize charts for each rack
    @foreach ($racksUnik as $index => $rack)
        (function() {
            const data = {
                perangkat: {{ $semuaRack->where('kode_region', $rack->kode_region)
                                ->where('kode_site', $rack->kode_site)
                                ->where('no_rack', $rack->no_rack)
                                ->whereNotNull('id_perangkat')->count() }},
                fasilitas: {{ $semuaRack->where('kode_region', $rack->kode_region)
                                ->where('kode_site', $rack->kode_site)
                                ->where('no_rack', $rack->no_rack)
                                ->whereNotNull('id_fasilitas')->count() }},
                idle: {{ $semuaRack->where('kode_region', $rack->kode_region)
                                ->where('kode_site', $rack->kode_site)
                                ->where('no_rack', $rack->no_rack)
                                ->whereNull('id_perangkat')
                                ->whereNull('id_fasilitas')->count() }}
            };

            const ctx = document.getElementById('pieChart{{ $index }}').getContext('2d');
            const pieChart = new Chart(ctx, {
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
                    maintainAspectRatio: false, // <- ini penting biar ukuran bisa fleksibel
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
                                // Only show label if value is greater than 0
                                if (value > 0) {
                                    const label = context.chart.data.labels[context.dataIndex];
                                    return `${label}: ${value}`;
                                }
                                return ''; // Return empty string for zero values
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });
        })();
    @endforeach
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
@endsection