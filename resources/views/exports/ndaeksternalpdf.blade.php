<!DOCTYPE html>
<html>

<head>
    <title>Formulir NDA</title>
    <style>
        @page {
            margin: 20px;
            /* Atur margin halaman */
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            /* Ukuran font yang lebih kecil */
            margin: 30px;
            /* Margin yang lebih kecil */
            text-align: justify;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            border: 1px solid black;
            padding: 5px;
            vertical-align: middle;
            text-align: center;
        }

        .logo {
            width: 25%;
        }

        .title {
            width: 50%;
            font-weight: bold;
        }

        .meta-table td {
            border: 1px solid black;
            padding: 4px;
            text-align: center;
            font-size: 11px;
        }

        .info-table {
            margin-top: 20px;
        }

        .info-table td {
            padding: 3px 0;
        }

        ol {
            padding-left: 20px;
        }

        p {
            margin: 10px 0;
        }

        .ttd {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }

        .ttd div {
            width: 45%;
            text-align: center;
        }
    </style>
</head>

<body>
    <!-- Header dengan border tertutup dan logo diperbesar -->
    <table style="width: 100%; border-collapse: collapse; font-size: 13px; font-weight: bold; border: 1px solid black;">
        <tr>
            <!-- Logo (kiri) -->
            <td rowspan="4"
                style="width: 25%; text-align: center; vertical-align: middle; border-right: 1px solid black; padding: 10px;">
                <img src="{{ public_path('img/pgncom.png') }}" alt="Logo" style="width: 100px; height: auto;">
            </td>

            <!-- Baris 1 -->
            <td style="border-bottom: 1px solid black; text-align: center; padding: 6px;">
                FORMULIR
            </td>
        </tr>
        <tr>
            <!-- Baris 2 -->
            <td style="border-bottom: 1px solid black; text-align: center; padding: 6px;">
                PERNYATAAN MENJAGA KERAHASIAAN INFORMASI
            </td>
        </tr>
        <tr>
            <!-- Baris 3 -->
            <td style="border-bottom: 1px solid black; text-align: center; padding: 6px;">
                (NON DISCLOSURE AGREEMENT)
            </td>
        </tr>
        <tr>
            <!-- Baris 4 -->
            <td style="text-align: center; padding: 6px;">
                PT PGAS TELEKOMUNIKASI NUSANTARA
            </td>
        </tr>
    </table>

    <!-- Metadata -->
    <table class="meta-table" style="margin-top: 20px;">
        <tr>
            <td>No. Dok: O-002/0.93/F02</td>
            <td>Rev: 00</td>
            <td>Tgl. Berlaku: 28 November 2019</td>
            <td>Hal: 1 dari 1</td>
        </tr>
    </table>

    @php
        $user = Auth::user();
    @endphp

    <table class="info-table" style="margin-top: 20px;">
        <tr>
            <td style="width: 100px;">Nama</td>
            <td>: {{ $user->name }}</td>
        </tr>
        <tr>
            <td>No. KTP</td>
            <td>: {{ $nda->no_ktp }}</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>: {{ $nda->alamat }}</td>
        </tr>
        <tr>
            <td>Perusahaan</td>
            <td>: {{ $user->perusahaan }}</td>
        </tr>
        <tr>
            <td>Region</td>
            <td>: {{ $user->region }}</td>
        </tr>
        <tr>
            <td>Bagian</td>
            <td>: {{ $user->bagian }}</td>
        </tr>
    </table>

    <p>Adalah Pekerja dari PT/CV {{ $user->perusahaan }} yang berkedudukan di {{ $user->region }}, dimana saya ditugaskan
        dan ditempatkan di bagian {{ $user->bagian }}, sehubungan dengan penugasan saya tersebut, saya menyatakan
        bersedia untuk:</p>

    <ol>
        <li>Menjaga kerahasiaan semua atau setiap bagian dari informasi Rahasia yaitu setiap informasi dan data PT PGAS
            Telekomunikasi Nusantara (PGNCOM), yang diperoleh secara langsung atau tidak langsung. ("Informasi
            Rahasia").</li>
        <li>Tidak mengungkapkan informasi Rahasia kepada pihak lain atau memanfaatkan atau menggunakannya untuk maksud
            apapun di luar tugas dan tanggung jawab saya sebagai Pegawai.</li>
        <li>Tidak menyalahgunakan wewenang akses ke sistem IT.</li>
        <li>Tidak menyebarkan User ID dan Password saya dan/atau User ID dan Password yang berhubungan dengan Perusahaan
            kepada orang yang tidak berhak.</li>
        <li>Apabila terbukti bahwa saya melakukan pelanggaran atas hal-hal diatas, maka saya bersedia dikenakan Sanksi
            sesuai dengan PT PGAS Telekomunikasi Nusantara (PGNCOM) dan Peraturan yang berlaku.</li>
    </ol>

    <p>Pernyataan ini tetap berlaku walaupun penugasan saya dari PT/CV {{ $nda->perusahaan }} di PT PGAS Telekomunikasi
        Nusantara (PGNCOM) telah berakhir atau diakhiri dan sesuai peraturan yang berlaku.</p>

    <p>Demikian, Pernyataan ini saya buat dalam keadaan sadar dan tanpa paksaan dari pihak manapun.</p>

    <!-- TTD -->
    <div style="margin-top: 80px;">
        <table style="width: 100%;">
            <tr>
                <td style="width: 50%; text-align: left;">
                    Jakarta, {{ \Carbon\Carbon::parse($nda->created_at)->format('d F Y') }}<br>
                    Hormat saya,<br><br><br>
                    <img src="{{ $nda->signature }}" alt="Tanda Tangan" style="width: 200px; height: auto;"><br>
                    ({{ $user->name }})
                </td>
                <td style="width: 50%; text-align: center;">
                    Mengetahui,<br><br>
                    <img src="{{ public_path('img/ttd.png') }}" alt="TTD Head"
                        style="width: 120px; height: auto;"><br><br><br>
                    <em>(Department Head of Network Reliability)</em>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>