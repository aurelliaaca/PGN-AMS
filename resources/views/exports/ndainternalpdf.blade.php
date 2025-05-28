<!DOCTYPE html>
<html>

<head>
    <title>NDA Internal PDF</title>
    <style>
        @page {
            margin: 20px;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 30px;
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
            margin-top: 20px;
            margin-bottom: 20px;
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
    <table style="width: 100%; border-collapse: collapse; font-size: 13px; font-weight: bold; border: 1px solid black;">
        <tr>
            <td rowspan="4"
                style="width: 25%; text-align: center; vertical-align: middle; border-right: 1px solid black; padding: 10px;">
                <img src="{{ public_path('img/pgncom.png') }}" alt="Logo" style="width: 100px; height: auto;">
            </td>

            <td style="border-bottom: 1px solid black; text-align: center; padding: 6px;">
                FORMULIR
            </td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid black; text-align: center; padding: 6px;">
                PERNYATAAN MENJAGA KERAHASIAAN INFORMASI
            </td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid black; text-align: center; padding: 6px;">
                (NON DISCLOSURE AGREEMENT)
            </td>
        </tr>
        <tr>
            <td style="text-align: center; padding: 6px;">
                PT PGAS TELEKOMUNIKASI NUSANTARA
            </td>
        </tr>
    </table>

    <table class="meta-table" style="margin-top: 20px;">
        <tr>
            <td>No. Dok: P-001/0.62/F01</td>
            <td>Rev: 00</td>
            <td>Tgl Berlaku: 11 November 2019</td>
            <td>Hal: 1 dari 1</td>
        </tr>
    </table>

    <p>
        Saya yang bertandatangan dibawah ini:
    </p>
    <table class="info-table">
        <tr>
            <td>Nama</td>
            <td>: {{ $nda->user->name }}</td>
        </tr>
        <tr>
            <td>No. KTP</td>
            <td>: {{ $nda->user->noktp }}</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>: {{ $nda->user->alamat }}</td>
        </tr>
    </table>

    <p>
        Sehubungan dengan penugasan saya tersebut, saya menyatakan bersedia untuk:
    </p>
    <ol>
        <li>Menjaga kerahasiaan semua atau setiap bagian dari informasi Rahasia yaitu setiap informasi dan data di PT
            PGAS Telekomunikasi Nusantara (PGNCOM), yang diperoleh secara langsung atau tidak langsung. ("Informasi
            Rahasia").</li>
        <li>Tidak mengungkapkan informasi Rahasia kepada pihak lain atau memanfaatkan atau menggunakannya untuk maksud
            apapun di luar tugas dan tanggung jawab saya sebagai Pegawai.</li>
        <li>Tidak menyalahgunakan wewenang atas akses ke system IT.</li>
        <li>Tidak menyebarkan <em>User ID</em> dan <em>Password</em> saya dan/atau <em>User ID</em> dan
            <em>Password</em> yang berhubungan dengan Perusahaan kepada orang lain yang tidak berhak.
        </li>
        <li>Apabila terbukti bahwa saya melakukan pelanggaran atas butir diatas, maka saya bersedia dikenakan Sanksi
            sesuai dengan peraturan yang berlaku di PT PGAS Telekomunikasi Nusantara (PGNCOM) dan peraturan
            perundang-undangan yang berlaku.</li>
    </ol>

    <p>
        Pernyataan ini tetap berlaku walaupun penugasan saya di PT PGAS Telekomunikasi Nusantara (PGNCOM) telah berakhir
        atau diakhiri.
    </p>
    <p>
        Demikian, Pernyataan ini saya buat dalam keadaan sadar dan tanpa paksaan dari pihak manapun.
    </p>

    <div style="margin-top: 40px;">
        <table style="width: 100%; text-align: center;">
            <tr>
                <td style="width: 33%;">Jakarta, {{ \Carbon\Carbon::parse($nda->created_at)->format('d F Y') }}</td>
                <td style="width: 33%;"></td>
                <td style="width: 33%;"></td>
            </tr>
            <tr>
                <td>Hormat saya,</td>
                <td></td>
                <td style="text-align: center;">Mengetahui,</td>
            </tr>
            <tr>
                <td colspan="3"><br></td>
            </tr>
            <tr>
                <td>
                    <img src="{{ $nda->signature }}" alt="Tanda Tangan" style="width: auto; height: auto; max-width: 50%;">
                </td>
                <td></td>
                <td style="text-align: center;">
                    @if($nda->signedBy && $nda->signedBy->signature)
                        <img src="{{ base_path('storage/app/public/' . $nda->signedBy->signature) }}" alt="TTD Head"
                            style="width:auto; height:auto; max-width: 50%;">
                    @endif
                </td>
            </tr>
            <tr>
                <td colspan="3"><br></td>
            </tr>
            <tr>
                <td>{{ $nda->user->name }}</td>
                <td></td>
                <td style="text-align: center;">{{ $nda->signedBy->name ?? '' }}</td>
            </tr>
        </table>
    </div>
</body>

</html>