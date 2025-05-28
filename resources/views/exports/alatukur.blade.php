<!DOCTYPE html>
<html>

<head>
    <title>Data Fasilitas</title>
    <style>
        @page {
            size: landscape;
        }

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
    <h3>Data Alatukur</h3>

    <div class="content">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Region</th>
                    <th>Alatukur</th>
                    <th>Alatukur ke</th>
                    <th>Brand</th>
                    <th>Type</th>
                    <th>Serial Number</th>
                    <th>Tahun Perolehan</th>
                    <th>Kondisi</th>
                    <th>Keterangan</th>
                    <th>Milik</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $index => $item)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>{{ $item->region->nama_region }}</td>
                        <td>{{ $item->jenisalatukur->nama_alatukur }}</td>
                        <td style="text-align: center;">{{ $item->alatukur_ke }}</td>
                        <td>{{ optional($item->brandalatukur)->nama_brand }}</td>
                        <td>{{ $item->type }}</td>
                        <td>{{ $item->serialnumber }}</td>
                        <td style="text-align: center;">{{ $item->tahunperolehan }}</td>
                        <td>{{ $item->kondisi }}</td>
                        <td>{{ $item->keterngan }}</td>
                        <td>{{ $item->user->name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <table style="width: 100%; margin-bottom: 60px; font-size: 12px; border: none;">
            <tr>
                <td style="text-align: left; border: none;">Dibuat: {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('j F Y H:i') }}</td>
                <td style="text-align: right; border: none;">Jakarta, {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('j F Y H:i') }}</td>
            </tr>
        </table>

        <div class="ttd" style="text-align: right;">
            <br><br><br>
            (....................................)
        </div>
    </div>

</body>

</html>