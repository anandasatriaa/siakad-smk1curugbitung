<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Raport - {{ $siswa->nama_siswa }} - {{ $semester }}</title>
    <style>
        @page { margin: 1cm; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 11pt; line-height: 1.3; color: #000; }
        
        /* Kop Surat */
        .kop-surat { border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; position: relative; }
        .logo { position: absolute; width: 80px; height: auto; top: 0; left: 0; }
        .header-text { text-align: center; margin-left: 80px; }
        .header-text h2 { margin: 0; font-size: 14pt; font-weight: bold; text-transform: uppercase; }
        .header-text h1 { margin: 0; font-size: 16pt; font-weight: bold; text-transform: uppercase; }
        .header-text p { margin: 2px 0; font-size: 9pt; }
        .header-text .info { font-style: italic; font-size: 8pt; }

        .judul-raport { text-align: center; margin: 20px 0; }
        .judul-raport h3 { margin: 0; text-decoration: underline; font-size: 13pt; text-transform: uppercase; }

        /* Tabel Identitas */
        .identitas { width: 100%; margin-bottom: 15px; border: none; }
        .identitas td { padding: 2px 0; vertical-align: top; border: none; font-size: 10pt; }

        /* Tabel Nilai */
        .table-nilai { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table-nilai th, .table-nilai td { border: 1px solid #000; padding: 5px; }
        .table-nilai th { background-color: #f2f2f2; font-weight: bold; text-align: center; font-size: 9pt; text-transform: uppercase; }
        .table-nilai td { font-size: 10pt; }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        
        /* Absensi */
        .table-absensi { width: 45%; border-collapse: collapse; margin-top: 20px; float: left; }
        .table-absensi th, .table-absensi td { border: 1px solid #000; padding: 4px 8px; font-size: 10pt; }
        .table-absensi th { background-color: #f2f2f2; text-align: left; }
        
        /* Tanda Tangan */
        .tanda-tangan { width: 100%; margin-top: 30px; clear: both; border: none; }
        .tanda-tangan td { text-align: center; width: 33%; vertical-align: top; border: none; font-size: 10pt; }
        .signature-space { height: 60px; }
    </style>
</head>
<body>
    <!-- KOP SURAT -->
    <div class="kop-surat">
        <img src="{{ public_path('assets/img/logo-smkn1curugbitung.png') }}" class="logo">
        <div class="header-text">
            <h2>PEMERINTAH PROVINSI BANTEN</h2>
            <h2>DINAS PENDIDIKAN DAN KEBUDAYAAN</h2>
            <h1>SMK NEGERI 1 CURUGBITUNG</h1>
            <p>Jl. Raya Curugbitung No. 01, Kec. Curugbitung, Kab. Lebak, Banten 42381</p>
            <p class="info">Email: smkn1curugbitung@gmail.com | Website: www.smkn1curugbitung.sch.id</p>
        </div>
    </div>

    <div class="judul-raport">
        <h3>LAPORAN HASIL BELAJAR SISWA (RAPORT)</h3>
    </div>

    <!-- IDENTITAS SISWA -->
    <table class="identitas">
        <tr>
            <td width="18%">Nama Siswa</td>
            <td width="42%">: <strong>{{ strtoupper($siswa->nama_siswa) }}</strong></td>
            <td width="15%">Kelas</td>
            <td width="25%">: {{ $siswa->kelas->nama_kelas }}</td>
        </tr>
        <tr>
            <td>Nomor Induk / NISN</td>
            <td>: {{ $siswa->nis }} / {{ $siswa->nisn }}</td>
            <td>Semester</td>
            <td>: {{ $semester }}</td>
        </tr>
        <tr>
            <td>Tahun Pelajaran</td>
            <td>: {{ $tahun_ajaran }}</td>
        </tr>
    </table>

    <!-- TABEL NILAI -->
    <table class="table-nilai">
        <thead>
            <tr>
                <th rowspan="2" width="5%">No</th>
                <th rowspan="2" width="35%">Mata Pelajaran</th>
                <th colspan="4">Capaian Kompetensi</th>
                <th rowspan="2" width="10%">Predikat</th>
            </tr>
            <tr>
                <th width="10%">Tugas</th>
                <th width="10%">UTS</th>
                <th width="10%">UAS</th>
                <th width="12%">Nilai Akhir</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse($nilais as $nilai)
                @php
                    $akhir = $nilai->nilai_akhir;
                    if ($akhir >= 88) $predikat = 'A';
                    elseif ($akhir >= 78) $predikat = 'B';
                    elseif ($akhir >= 70) $predikat = 'C';
                    else $predikat = 'D';
                @endphp
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td class="text-left">{{ $nilai->mataPelajaran->nama_mapel }}</td>
                    <td class="text-center">{{ number_format($nilai->nilai_tugas, 0) }}</td>
                    <td class="text-center">{{ number_format($nilai->nilai_uts, 0) }}</td>
                    <td class="text-center">{{ number_format($nilai->nilai_uas, 0) }}</td>
                    <td class="text-center"><strong>{{ number_format($akhir, 0) }}</strong></td>
                    <td class="text-center"><strong>{{ $predikat }}</strong></td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">Data nilai belum tersedia untuk periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- TABEL ABSENSI -->
    <table class="table-absensi">
        <thead>
            <tr>
                <th colspan="2">Kehadiran (Absensi)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td width="70%">Sakit (S)</td>
                <td class="text-center">{{ $absensiSummary['sakit'] }} hari</td>
            </tr>
            <tr>
                <td>Izin (I)</td>
                <td class="text-center">{{ $absensiSummary['izin'] }} hari</td>
            </tr>
            <tr>
                <td>Tanpa Keterangan (A)</td>
                <td class="text-center">{{ $absensiSummary['alpa'] }} hari</td>
            </tr>
        </tbody>
    </table>

    <!-- TANDA TANGAN -->
    <table class="tanda-tangan">
        <tr>
            <td>
                <br>
                Mengetahui,<br>
                Orang Tua / Wali Siswa<br>
                <div class="signature-space"></div>
                ( ......................................... )
            </td>
            <td></td>
            <td>
                Curugbitung, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                Wali Kelas<br>
                <div class="signature-space"></div>
                <strong><u>{{ $siswa->kelas->wali_kelas->nama_guru ?? '( ....................... )' }}</u></strong><br>
                NIP. {{ $siswa->kelas->wali_kelas->nip ?? '-' }}
            </td>
        </tr>
    </table>
</body>
</html>
