@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Akademik & Laporan /</span> Melihat Absensi
    </h4>

    <!-- Form Filter -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Filter Laporan Absensi</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.laporan.absensi') }}" method="GET">
                <div class="row gx-3 gy-2 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label" for="kelas_id">Kelas</label>
                        <select class="form-select" id="kelas_id" name="kelas_id" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelasList as $kelas)
                                <option value="{{ $kelas->id }}" {{ $kelas_id == $kelas->id ? 'selected' : '' }}>
                                    {{ $kelas->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="bulan">Bulan</label>
                        <select class="form-select" id="bulan" name="bulan" required>
                            @php
                                $months = [
                                    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
                                    '04' => 'April', '05' => 'Mei', '06' => 'Juni',
                                    '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
                                    '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                                ];
                            @endphp
                            @foreach($months as $num => $name)
                                <option value="{{ $num }}" {{ str_pad($bulan, 2, '0', STR_PAD_LEFT) == $num ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="tahun">Tahun</label>
                        <select class="form-select" id="tahun" name="tahun" required>
                            @php
                                $startYear = 2024;
                                $endYear = max($startYear, (int)date('Y') + 5);
                            @endphp
                            @for($y = $startYear; $y <= $endYear; $y++)
                                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100"><i class="bx bx-search me-1"></i> Tampilkan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Laporan -->
    @if($kelas_id)
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Rekapitulasi Absensi Siswa</h5>
            </div>
            <div class="card-body">
                @if(count($siswas) > 0)
                    <div class="table-responsive text-nowrap mb-3">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th rowspan="2" class="align-middle text-center">No</th>
                                    <th rowspan="2" class="align-middle text-center">NIS</th>
                                    <th rowspan="2" class="align-middle text-center">Nama Siswa</th>
                                    <th colspan="4" class="text-center">Jumlah Kehadiran</th>
                                </tr>
                                <tr>
                                    <th class="text-center text-success">Hadir</th>
                                    <th class="text-center text-warning">Sakit</th>
                                    <th class="text-center text-info">Izin</th>
                                    <th class="text-center text-danger">Alpa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($siswas as $index => $s)
                                    @php
                                        $hadir = isset($absensiData[$s->id]['hadir']) ? $absensiData[$s->id]['hadir'] : 0;
                                        $sakit = isset($absensiData[$s->id]['sakit']) ? $absensiData[$s->id]['sakit'] : 0;
                                        $izin  = isset($absensiData[$s->id]['izin']) ? $absensiData[$s->id]['izin'] : 0;
                                        $alpa  = isset($absensiData[$s->id]['alpa']) ? $absensiData[$s->id]['alpa'] : 0;
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>{{ $s->nis }}</td>
                                        <td>{{ $s->nama_siswa }}</td>
                                        <td class="text-center fw-bold text-success">{{ $hadir }}</td>
                                        <td class="text-center fw-bold text-warning">{{ $sakit }}</td>
                                        <td class="text-center fw-bold text-info">{{ $izin }}</td>
                                        <td class="text-center fw-bold text-danger">{{ $alpa }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-warning" role="alert">
                        Tidak ada data siswa untuk kelas ini.
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection
