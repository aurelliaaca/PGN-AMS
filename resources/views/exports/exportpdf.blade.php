<!DOCTYPE html>
<html>
<head>
    <title>Data Perangkat</title>
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            font-size: 12px;
            padding: 4px;
        }
    </style>
</head>
<body>
    <h3>Data Perangkat</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Region</th>
                <th>Site</th>
                <th>No Rack</th>
                <th>Perangkat</th>
                <th>Perangkat ke</th>
                <th>Brand</th>
                <th>Type</th>
                <th>UAwal</th>
                <th>UAkhir</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->region->nama_region }}</td>
                <td>{{ $item->site->nama_site }}</td>
                <td>{{ $item->no_rack }}</td>
                <td>{{ $item->jenisperangkat->nama_perangkat }}</td>
                <td>{{ $item->perangkat_ke }}</td>
                <td>{{ optional($item->brandperangkat)->nama_brand }}</td>
                <td>{{ $item->type }}</td>
                <td>{{ $item->uawal }}</td>
                <td>{{ $item->uakhir }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
