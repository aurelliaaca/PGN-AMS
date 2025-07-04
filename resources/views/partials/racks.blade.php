@if($racks->isEmpty())
    <div class="no-data-message" style="text-align: center; padding: 20px;">
        <i class="fas fa-info-circle" style="color: #4f52ba; font-size: 24px;"></i>
        <p style="color: #4f52ba; margin-top: 10px;">Tidak ada rack yang ditemukan</p>
    </div>
@else
    <div class="card-grid" style="margin-top: 20px;">
        @foreach($racks as $rack)
            @php
                $chartId = "pieChart-{$rack->kode_region}-{$rack->kode_site}-{$rack->no_rack}";
                $tableId = "table-{$rack->kode_region}-{$rack->kode_site}-{$rack->no_rack}";
            @endphp
            <div class="toggle">
                <div class="card-item primary">
                    <div class="icon-wrapper-chart">
                        <canvas id="{{ $chartId }}" style="width: 150px; height: 150px;"></canvas>
                    </div>
                    <div class="card-content">
                        <h4>Rack {{ $rack->no_rack }}</h4>
                        <p>{{ $rack->site->nama_site }}, {{ $rack->region->nama_region }}</p>
                        <p>Jumlah Perangkat: {{ $rack->device_count }} | Jumlah Fasilitas: {{ $rack->facility_count }}</p>
                        <div class="action-buttons left-align">
                            <button class="btn btn-eye" style="margin-top:10px;" onclick="toggleTable('{{ $tableId }}')">
                                <i class="fas fa-eye"></i>
                            </button>
                            @if(auth()->user()->role == '1')
                                <button class="btn btn-delete" style="margin-top:10px;"
                                    onclick="confirmDeleteRack('{{ $rack->kode_region }}', '{{ $rack->kode_site }}', '{{ $rack->no_rack }}')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <div id="{{ $tableId }}" class="tables-container">
                    <div class="table table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID U</th>
                                    <th>ID Perangkat/Fasilitas</th>
                                    @if(auth()->user()->role == '1' || auth()->user()->role == '2')
                                        <th>Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rack->details as $detail)
                                    @php
                                        $deviceInfo = 'IDLE';
                                        $isUserPosition = auth()->user()->role == 1 || auth()->user()->role == 2 || $detail->milik == auth()->user()->id || $detail->kode_region == auth()->user()->region;

                                        if ($isUserPosition) {
                                            if ($detail->id_perangkat && $detail->listperangkat) {
                                                $deviceCode = [
                                                    $detail->listperangkat->kode_region,
                                                    $detail->listperangkat->kode_site,
                                                    $detail->listperangkat->no_rack,
                                                    $detail->listperangkat->kode_perangkat,
                                                    $detail->listperangkat->perangkat_ke,
                                                    $detail->listperangkat->kode_brand,
                                                    $detail->listperangkat->type
                                                ];
                                                $deviceInfo = implode('-', array_filter($deviceCode)) ?: $detail->id_perangkat;
                                            } elseif ($detail->id_fasilitas && $detail->listfasilitas) {
                                                $facilityCode = [
                                                    $detail->listfasilitas->kode_region,
                                                    $detail->listfasilitas->kode_site,
                                                    $detail->listfasilitas->no_rack,
                                                    $detail->listfasilitas->kode_fasilitas,
                                                    $detail->listfasilitas->perangkat_ke,
                                                    $detail->listfasilitas->kode_brand,
                                                    $detail->listfasilitas->type
                                                ];
                                                $deviceInfo = implode('-', array_filter($facilityCode)) ?: $detail->id_fasilitas;
                                            }
                                        }
                                        $showDeleteButton = $isUserPosition;
                                    @endphp
                                    <tr>
                                        <td>{{ $detail->u }}</td>
                                        <td>{{ $deviceInfo }}</td>
                                        @if(auth()->user()->role == '1' || auth()->user()->role == '2')
                                            <td>
                                                @if($showDeleteButton)
                                                    <button class="btn btn-delete"
                                                        onclick="confirmDeleteU('{{ $rack->kode_region }}', '{{ $rack->kode_site }}', '{{ $rack->no_rack }}', '{{ $detail->u }}')">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif