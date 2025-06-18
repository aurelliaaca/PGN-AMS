<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Data Center Authorization Form</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 11pt;
      line-height: 1.3;
      margin: 0.25in 0.25in 0.5in 0.25in;
    }

    h2 {
      text-align: center;
      text-transform: uppercase;
      margin-bottom: 18pt;
      font-size: 16pt;
      font-weight: bold;
      font-style: italic;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 12pt;
      font-size: 11pt;
    }

    td,
    th {
      border: 1px solid #000;
      padding: 4pt 6pt;
      vertical-align: top;
    }

    .no-border td {
      border: none;
      padding: 2pt 6pt;
    }

    .label {
      font-weight: bold;
    }

    .sub-label {
      font-size: 9pt;
      font-style: italic;
      color: #333;
      display: block;
      margin-top: pt;
    }

    .section-title {
      font-weight: bold;
      margin: 12pt 0 6pt 0;
    }

    .section-title em {
      font-weight: normal;
      font-size: 10pt;
    }

    .section-number {
      width: 20pt;
      vertical-align: top;
      font-weight: bold;
      font-size: 10pt;
    }

    th {
      background-color: #ffff;
      text-align: center;
    }

    p {
      margin-bottom: 10pt;
    }

    ol {
      padding-left: 18pt;
      margin-bottom: 12pt;
    }

    ol li {
      margin-bottom: 4pt;
    }

    small em {
      display: block;
      margin-top: 4pt;
      font-size: 9pt;
      color: #333;
    }

    .signature-table td {
      text-align: center;
      height: 100px;
    }

    .signature-title {
      font-weight: bold;
      text-align: center;
      padding: 6pt;
    }

    .signature-block td {
      padding: 4pt 6pt;
    }
  </style>
</head>

<body>
  <h2>DATA CENTER AUTHORIZATION FORM</h2>

  <table class="no-border">
    <tr>
      <td class="section-number">1</td>
      <td class="label">Nama Pemohon</td>
      <td>: {{$dcaf->user->name}}</td>
      <td class="label">No Hp</td>
      <td>: {{$dcaf->user->mobile_number}}</td>
    </tr>
    <tr>
      <td></td>
      <td class="sub-label">Name of Applicant</td>
      <td></td>
      <td class="sub-label">Hp Number</td>
      <td></td>
    </tr>
    <tr>
      <td class="section-number">2</td>
      <td class="label">Pengawas Lapangan</td>
      <td>: {{ $dcaf->pengawasDCAF->name ?? '' }}</td>
      <td class="label">No Hp</td>
      <td>: {{ $dcaf->pengawasDCAF->mobile_number ?? '' }}</td>
    </tr>
    <tr>
      <td></td>
      <td class="sub-label">Field Supervisor</td>
      <td></td>
      <td class="sub-label">Hp Number</td>
      <td></td>
    </tr>
    <tr>
      <td class="section-number">3</td>
      <td class="label">Divisi</td>
      <td colspan="3">: {{$dcaf->user->bagian}}</td>
    </tr>
    <tr>
      <td class="section-number">4</td>
      <td class="label">Rekanan</td>
      <td colspan="3">: </td>
    </tr>
    <tr>
      <td></td>
      <td class="sub-label">3rd party</td>
      <td colspan="3"></td>
    </tr>
  </table>

  <table>
    <thead>
      <tr>
        <th>No<br /><span class="sub-label">No</span></th>
        <th>Nama<br /><span class="sub-label">Name</span></th>
        <th>Perusahaan<br /><span class="sub-label">Company</span></th>
        <th>KTP ID<br /><span class="sub-label">ID</span></th>
        <th>Telephone<br /><span class="sub-label">Phone Number</span></th>
      </tr>
    </thead>
    <tbody>
      @foreach($dcaf->rekanans as $index => $r)
      <tr>
      <td>{{ $index + 1 }}</td>
      <td>{{ $r->nama }}</td>
      <td>{{ $r->perusahaan }}</td>
      <td>{{ $r->ktp }}</td>
      <td>{{ $r->telp }}</td>
      </tr>
    @endforeach
    </tbody>
  </table>
  <table class="no-border">
    <tr>
      <td class="section-number">5</td>
      <td class="label">Tanggal Mulai</td>
      <td>: {{$dcaf->tanggal_mulai}}</td>
      <td class="label">Tanggal Selesai</td>
      <td>: {{$dcaf->tanggal_selesai}}</td>
    </tr>
    <tr>
      <td></td>
      <td class="sub-label">Start Date</td>
      <td></td>
      <td class="sub-label">End Date</td>
      <td></td>
    </tr>
    <tr>
      <td class="section-number">6</td>
      <td class="label">Waktu Mulai</td>
      <td>: {{$dcaf->waktu_mulai}}</td>
      <td class="label">Waktu Selesai</td>
      <td>: {{$dcaf->waktu_selesai}}</td>
    </tr>
    <tr>
      <td></td>
      <td class="sub-label">Start Time</td>
      <td></td>
      <td class="sub-label">End Time</td>
      <td></td>
    </tr>
    <tr>
      <td class="section-number">7</td>
      <td class="label">Lokasi Pekerjaan</td>
      <td>: {{$dcaf->lokasi}}</td>
      <td class="label">Nomor Rak</td>
      <td>: {{$dcaf->no_rack}}</td>
    </tr>
    <tr>
      <td></td>
      <td class="sub-label">Work Location</td>
      <td></td>
      <td class="sub-label">Rack Number</td>
      <td></td>
    </tr>
    <tr>
      <td class="section-number">8</td>
      <td class="label">Jenis Pekerjaan</td>
      <td>: {{$dcaf->jenis_pekerjaan}}</td>
      <td class="sub-label" style="text-align: justify; font-size: 7pt;">*Harap dirincikan apabila memilih opsi "Others"
      </td>
    </tr>
    <tr>
      <td></td>
      <td class="sub-label">Type Of Work</td>
    </tr>
    <tr>
      <td class="section-number">9</td>
      <td class="label">Deskripsi Pekerjaan</td>
      <td colspan="3">: {{ $dcaf->deskripsi_pekerjaan ?? '' }}</td>
    </tr>
    <tr>
      <td></td>
      <td class="sub-label">Job Description</td>
      <td colspan="3"></td>
    </tr>
  </table>
  <p class="section-title">
    10 &nbsp; Daftar Perlengkapan: <br /><em>List of Equipment</em>
  </p>

  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>
          Nama Perlengkapan<span class="sub-label">Equipment Name</span>
        </th>
        <th>Jumlah<span class="sub-label">Amount</span></th>
        <th>Keterangan<span class="sub-label">Information</span></th>
      </tr>
    </thead>
    <tbody>
      @foreach($dcaf->perlengkapans as $index => $item)
      <tr>
      <td>{{ $index + 1 }}</td>
      <td>{{ $item['nama'] }}</td>
      <td>{{ $item['jumlah'] }}</td>
      <td>{{ $item['keterangan'] }}</td>
      </tr>
    @endforeach
    </tbody>
  </table>

  <p>
    <strong>11 &nbsp; Detail Barang Masuk :</strong><br />
    <em>Details of incoming item</em>
  </p>

  <table border="1" cellspacing="0" cellpadding="5">
    <thead>
      <tr>
        <th>No</th>
        <th>Nama<br /><span class="sub-label">Name</span></th>
        <th>Berat (kg)<br /><span class="sub-label">Weight</span></th>
        <th>Jumlah<br /><span class="sub-label">Amount</span></th>
        <th>Keterangan<br /><span class="sub-label">Information</span></th>
      </tr>
    </thead>
    <tbody>
      @foreach($dcaf->barangMasuks as $index => $item)
      <tr>
      <td>{{ $index + 1 }}</td>
      <td>{{ $item['nama'] }}</td>
      <td>{{ $item['berat'] }}</td>
      <td>{{ $item['jumlah'] }}</td>
      <td>{{ $item['keterangan'] }}</td>
      </tr>
    @endforeach
    </tbody>
  </table>

  <p>
    <small>
      <em> *isi nama Barang yang akan dipasang/diinstal di Data Center. </em>
    </small>
  </p>

  <p>
    <strong>12 &nbsp; Detail Barang Keluar :</strong><br />
    <em>Detail of Outgoing items</em>
  </p>

  <table border="1" cellspacing="0" cellpadding="5">
    <thead>
      <tr>
        <th>No</th>
        <th>Nama<br /><span class="sub-label">Name</span></th>
        <th>Berat (kg)<br /><span class="sub-label">Weight</span></th>
        <th>Jumlah<br /><span class="sub-label">Amount</span></th>
        <th>Keterangan<br /><span class="sub-label">Information</span></th>
      </tr>
    </thead>
    <tbody>
      @foreach($dcaf->barangKeluars as $index => $item)
      <tr>
      <td>{{ $index + 1 }}</td>
      <td>{{ $item['nama'] }}</td>
      <td>{{ $item['berat'] }}</td>
      <td>{{ $item['jumlah'] }}</td>
      <td>{{ $item['keterangan'] }}</td>
      </tr>
    @endforeach
    </tbody>
  </table>

  <p>
    <small>
      <em>
        *isi nama Barang yang akan di dismantle/dicabut dari Data Center.
      </em>
    </small>
  </p>

  <div style="text-align: justify; page-break-before: always;">
    <p>
      <strong>13 Peraturan Data Center :</strong><br />
      <em>Regulations of Data Center</em>
    </p>

    <ol type="a">
      <li>
        Setiap Pekerjaan di <em>Data Center</em> wajib membawa
        <strong>DATA CENTER AUTHORIZATION FORM</strong> dan disampaikan setiap
        hari sebelum memulai pekerjaan kepada Petugas yang Standby di
        <em>Data Center</em>.
      </li>
      <li>
        Pada hari terakhir Pekerjaan di <em>Data Center</em>,
        <strong>DATA CENTER AUTHORIZATION FORM</strong> wajib dikembalikan ke
        Petugas yang Standby.
      </li>
      <li>
        Setiap Pekerjaan di <em>Data Center</em> wajib didampingi oleh
        <strong>Pengawas Lapangan</strong> atau
        <strong>Field Supervisor</strong> sampai dengan pekerjaan selesai.
      </li>
      <li>
        Setiap Barang yang akan dikoneksikan pada <em>electrical panel</em> di
        <em>Data Center</em>, data sheet barang tersebut wajib dilampirkan saat
        pengajuan <strong>DATA CENTER AUTHORIZATION FORM</strong>.
      </li>
      <li>
        Dilarang menggunakan PDU yang ada didalam rack untuk melakukan proses
        charger Laptop dan barang lainnya, Pihak <em>Data Center</em> akan
        memfasilitasi roll kabel untuk sumber daya power pada media yang
        digunakan.
      </li>
      <li>
        Setiap pengunjung <em>Data Center</em> wajib menjaga kebersihan di dalam
        dan di luar <em>Data Center</em>.
      </li>
      <li>
        Pengunjung wajib melepas alas kaki di depan pintu masuk
        <em>Data Center</em>.
      </li>
      <li>
        <strong>DATA CENTER AUTHORIZATION FORM</strong> wajib disetujui maksimal
        2 (dua) hari kerja sebelum Pekerjaan dimulai, kecuali dalam hal
        <strong>Emergency</strong> dengan menyampaikan penjelasan rinci sebagai
        bentuk tanggap tersebut.
      </li>
      <li>
        <strong>DATA CENTER AUTHORIZATION FORM</strong> berlaku hanya untuk 1
        (satu) lokasi Pekerjaan dan berlaku maksimal 7 (tujuh) hari kalender.
      </li>
      <li>
        Pengunjung <em>Data Center</em> diwajibkan untuk menaati seluruh
        ketentuan peraturan dan prosedur baik tertulis maupun tidak tertulis
        yang berlaku di <em>Data Center</em>.
      </li>
    </ol>
  </div>

  <div style="text-align: center; margin-top: 30px; page-break-inside: avoid;">
    <table style="width: 70%; border-collapse: collapse; margin-bottom: 16px; margin-left: auto; margin-right: auto;">
      <tr>
        <th style="width: 35%; text-align: center; font-size: 14px; border: 1px solid #000;">Pemohon</th>
        <th style="width: 35%; text-align: center; font-size: 14px; border: 1px solid #000;">Menyetujui</th>
      </tr>
      <tr>
        <td style="height: 70px; border: 1px solid #000; text-align: center; vertical-align: middle;">
          @if(isset($dcaf->signature) && !empty($dcaf->signature))
        <img src="{{ $dcaf->signature }}" alt="Tanda Tangan Pemohon" style="max-width: 100%; max-height: 68px;">
      @endif
        </td>
        <td style="height: 70px; border: 1px solid #000; text-align: center; vertical-align: middle;">
          @if($dcaf->signedBy && $dcaf->signedBy->signature)
        <img src="{{ base_path('storage/app/public/' . $dcaf->signedBy->signature) }}" alt="Tanda Tangan Penyetuju"
        style="max-width: 100%; max-height: 68px;">
      @endif
        </td>
      </tr>
      <tr>
        <td style="border: 1px solid #000; padding: 5px;">Tanggal : {{ $dcaf->created_at }}</td>
        <td style="border: 1px solid #000; padding: 5px;">
          @if($dcaf->signedBy)
        Tanggal: {{ $dcaf->updated_at }}
      </td>
    @endif
      </tr>
      <tr>
        <td style="border: 1px solid #000; padding: 5px;">Nama : {{ $dcaf->user->name }}</td>
        <td style="border: 1px solid #000; padding: 5px;">
          @if($dcaf->signedBy)
        Nama : {{ $dcaf->signedBy->name ?? '' }}
      </td>
    @endif
      </tr>
    </table>
  </div>

  <div style="text-align: center; margin-top: 30px; page-break-inside: avoid;">
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 8px;">
      <tr>
        <th colspan="6" style="text-align: center; font-size: 14px; border: 1px solid #000; padding: 6px 0;">
          Mengetahui*)</th>
      </tr>
      <tr>
        <th style="width: 20%; border: 1px solid #000; text-align: center;">Nama Petugas</th>
        <th style="width: 13%; border: 1px solid #000; text-align: center;">Tanggal</th>
        <th style="width: 13%; border: 1px solid #000; text-align: center;">Jam Mulai</th>
        <th style="width: 13%; border: 1px solid #000; text-align: center;">Jam Selesai</th>
        <th style="width: 10%; border: 1px solid #000; text-align: center;">Paraf</th>
        <th style="width: 31%; border: 1px solid #000; text-align: center;">Catatan Petugas</th>
      </tr>
      <tr>
        <td style="height: 28px; border: 1px solid #000;"></td>
        <td style="border: 1px solid #000;"></td>
        <td style="border: 1px solid #000;"></td>
        <td style="border: 1px solid #000;"></td>
        <td style="border: 1px solid #000;"></td>
        <td style="border: 1px solid #000;"></td>
      </tr>
      <tr>
        <td style="height: 28px; border: 1px solid #000;"></td>
        <td style="border: 1px solid #000;"></td>
        <td style="border: 1px solid #000;"></td>
        <td style="border: 1px solid #000;"></td>
        <td style="border: 1px solid #000;"></td>
        <td style="border: 1px solid #000;"></td>
      </tr>
    </table>
    <small><em>*)diisi dan ditandatangani setiap hari oleh Petugas yang mendampingi di Data Center.</em></small>
  </div>
</body>

</html>