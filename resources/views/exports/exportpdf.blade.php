<!DOCTYPE html>
<html>

<head>
    <title>Data Perangkat</title>
    <style>
        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
            font-size: 12px;
            padding: 4px;
            width: 100%;
        }

        .footer {
            margin-top: 20px;
            width: 100%;
            position: relative;
            page-break-inside: avoid;
        }

        .footer .tanggal {
            text-align: right;
            margin-bottom: 60px;
            font-size: 12px;
        }

        .footer .ttd {
            text-align: right;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <h3>Data Perangkat</h3>

    <div class="content">
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
    </div>

    <div class="footer">
        <div class="tanggal">
            Semarang, {{ \Carbon\Carbon::now()->format('d F Y') }}
        </div>
        <div class="ttd">
            <br><br><br>
            (....................................)
        </div>
    </div>
</body>

</html>