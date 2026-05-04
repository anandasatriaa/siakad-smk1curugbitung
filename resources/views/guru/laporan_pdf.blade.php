<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Siswa - {{ $siswa->nama_siswa }}</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; line-height: 1.5; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h3, .header h4, .header p { margin: 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; text-align: center; }
        .text-center { text-align: center; }
        .info-table { border: none; margin-bottom: 20px; width: 100%; }
        .info-table td { border: none; padding: 4px; }
        .footer-sig { width: 100%; margin-top: 40px; border: none; }
        .footer-sig td { border: none; text-align: center; vertical-align: top; }
    </style>
</head>
<body>
    <div class="header">
        <h3>LAPORAN HASIL BELAJAR SISWA</h3>
        <h4>SMK BAKTI IDHATA</h4>
        <hr>
    </div>

    <table class="info-table">
        <tr>
            <td width="15%">Nama Siswa</td>
            <td width="35%">: <strong>{{ $siswa->nama_siswa }}</strong></td>
            <td width="15%">Kelas</td>
            <td width="35%">: <strong>{{ $siswa->kelas->nama_kelas }}</strong></td>
        </tr>
        <tr>
            <td>NIS</td>
            <td>: {{ $siswa->nis }}</td>
            <td>Semester</td>
            <td>: {{ $semester }}</td>
        </tr>
        <tr>
            <td>Tahun Ajaran</td>
            <td>: {{ $tahun_ajaran }}</td>
            <td>Wali Kelas</td>
            <td>: {{ $siswa->kelas->wali_kelas->nama_guru ?? '-' }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th width="10%">No</th>
                <th width="60%">Mata Pelajaran</th>
                <th width="30%">Nilai Akhir</th>
            </tr>
        </thead>
        <tbody>
            @forelse($nilais as $index => $nilai)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $nilai->mataPelajaran->nama_mapel }}</td>
                    <td class="text-center">{{ number_format($nilai->nilai_akhir, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">Belum ada nilai yang diinput.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="width: 50%;">
        <table>
            <thead>
                <tr>
                    <th colspan="2">Ketidakhadiran</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Sakit</td>
                    <td class="text-center">{{ $absensiSummary['sakit'] }} hari</td>
                </tr>
                <tr>
                    <td>Izin</td>
                    <td class="text-center">{{ $absensiSummary['izin'] }} hari</td>
                </tr>
                <tr>
                    <td>Tanpa Keterangan</td>
                    <td class="text-center">{{ $absensiSummary['alpa'] }} hari</td>
                </tr>
            </tbody>
        </table>
    </div>

    <table class="footer-sig">
        <tr>
            <td width="33%">
                <br>Orang Tua / Wali<br><br><br><br>
                ( ......................................... )
            </td>
            <td width="33%"></td>
            <td width="33%">
                Jakarta, {{ \Carbon\Carbon::now()->format('d M Y') }}<br>Wali Kelas<br><br><br><br>
                <strong>{{ $siswa->kelas->wali_kelas->nama_guru ?? '( ....................... )' }}</strong>
            </td>
        </tr>
    </table>
</body>
</html>
