<!DOCTYPE html>
<html>

<head>
    <title>Data Fasilitas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.3;
            margin: 0.25in 0.25in 0.5in 0.25in;
        }

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
    <h3>Data Fasilitas</h3>

    <div class="content">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Region</th>
                    <th>Site</th>
                    <th>No Rack</th>
                    <th>Fasilitas</th>
                    <th>Fasilitas ke</th>
                    <th>Brand</th>
                    <th>Type</th>
                    <th>UAwal</th>
                    <th>UAkhir</th>
                            'id_fasilitas',
        'kode_region',
        'kode_site',
        'no_rack',
        'kode_fasilitas',
        'fasilitas_ke',
        'kode_brand',
        'type',
        'serialnumber',
        'jml_fasilitas',
        'status',
        'uawal',
        'uakhir',
        'milik',
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $index => $item)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>{{ $item->region->nama_region }}</td>
                        <td>{{ $item->site->nama_site }}</td>
                        <td style="text-align: center;">{{ $item->no_rack }}</td>
                        <td>{{ $item->jenisperangkat->nama_perangkat }}</td>
                        <td style="text-align: center;">{{ $item->perangkat_ke }}</td>
                        <td>{{ optional($item->brandperangkat)->nama_brand }}</td>
                        <td>{{ $item->type }}</td>
                        <td style="text-align: center;">{{ $item->uawal }}</td>
                        <td style="text-align: center;">{{ $item->uakhir }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <table style="width: 100%; margin-bottom: 60px; font-size: 12px; border: none;">
            <tr>
                <td style="text-align: left; border: none;">Dibuat: {{ now()->format('d/m/Y H:i:s') }}</td>
                <td style="text-align: right; border: none;">Jakarta, {{ \Carbon\Carbon::now()->format('d F Y') }}</td>
            </tr>
        </table>

        <div class="ttd" style="text-align: right;">
            <br><br><br>
            (....................................)
        </div>
    </div>

</body>
</html>