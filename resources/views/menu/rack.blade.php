@extends('layouts.app')
@section('title', 'Rack') 
@section('page_title', 'Rack') 
@section('content')
    <div class="main">
    <div class="card-section">
    @foreach ($racksUnik as $index => $rack)
    <div class="toggle">
        <div class="card-item" onclick="toggleTable({{ $index }})">
            <div class="card-icon">
                <i class="fas fa-server"></i>
            </div>
            <div class="card-content">
                <h4>Rack: {{ $rack->no_rack }}</h4>
                <p>{{ $rack->kode_region }} - {{ $rack->kode_site }}</p>
            </div>
        </div>

        <div id="table-{{ $index }}" style="display: none; margin-top: 10px;">
            <table class="table table-bordered text-sm">
                <thead>
                    <tr>
                        <th>U</th>
                        <th>ID Perangkat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($semuaRack->where('kode_region', $rack->kode_region)
                                       ->where('kode_site', $rack->kode_site)
                                       ->where('no_rack', $rack->no_rack) as $item)
                        <tr>
                            <td>{{ $item->u }}</td>
                            <td>{{ $item->id_perangkat }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
</div>
    @endforeach
</div>

    </div>
    <script>
    function toggleTable(index) {
        const table = document.getElementById('table-' + index);
        table.style.display = table.style.display === 'none' ? 'block' : 'none';
    }
</script>

@endsection