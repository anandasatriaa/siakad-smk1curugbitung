@extends('layouts.app')

@section('title', 'Laporan Nilai')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Akademik & Laporan /</span> Laporan Nilai
    </h4>

    <!-- Form Filter -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Filter Laporan Nilai</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.laporan.nilai') }}" method="GET">
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
                        <label class="form-label" for="semester">Semester</label>
                        <select class="form-select" id="semester" name="semester" required>
                            <option value="Ganjil" {{ $semester == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                            <option value="Genap" {{ $semester == 'Genap' ? 'selected' : '' }}>Genap</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="tahun_ajaran">Tahun Ajaran</label>
                        <select class="form-select" id="tahun_ajaran" name="tahun_ajaran" required>
                            @php
                                $startYear = 2024;
                                $currentYear = (int)date('Y');
                                $endYear = max($startYear, $currentYear + 5);
                            @endphp
                            @for($y = $startYear; $y <= $endYear; $y++)
                                @php $ta = $y . '/' . ($y + 1); @endphp
                                <option value="{{ $ta }}" {{ $tahun_ajaran == $ta ? 'selected' : '' }}>{{ $ta }}</option>
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
                <h5 class="mb-0">Data Nilai Siswa</h5>
            </div>
            <div class="card-body">
                @if(count($siswas) > 0)
                    <div class="table-responsive text-nowrap mb-3">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th rowspan="3" class="align-middle text-center">No</th>
                                    <th rowspan="3" class="align-middle text-center">NIS</th>
                                    <th rowspan="3" class="align-middle text-center">Nama Siswa</th>
                                    @if(count($mapels) > 0)
                                        <th colspan="{{ count($mapels) * 4 }}" class="text-center">Mata Pelajaran</th>
                                    @else
                                        <th rowspan="3" class="align-middle text-center">Nilai Mata Pelajaran</th>
                                    @endif
                                </tr>
                                @if(count($mapels) > 0)
                                    <tr>
                                        @foreach($mapels as $mapel)
                                            <th colspan="4" class="text-center small">{{ $mapel->nama_mapel }}</th>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        @foreach($mapels as $mapel)
                                            <th class="text-center small" style="font-size: 0.7rem;">TGS</th>
                                            <th class="text-center small" style="font-size: 0.7rem;">UTS</th>
                                            <th class="text-center small" style="font-size: 0.7rem;">UAS</th>
                                            <th class="text-center small bg-light" style="font-size: 0.7rem;">AKH</th>
                                        @endforeach
                                    </tr>
                                @endif
                            </thead>
                            <tbody>
                                @foreach($siswas as $index => $s)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>{{ $s->nis }}</td>
                                        <td>{{ $s->nama_siswa }}</td>
                                        @if(count($mapels) > 0)
                                            @foreach($mapels as $mapel)
                                                @php
                                                    $nilai = $nilaiData[$s->id][$mapel->id] ?? null;
                                                @endphp
                                                <td class="text-center small">{{ $nilai ? number_format($nilai->nilai_tugas, 0) : '-' }}</td>
                                                <td class="text-center small">{{ $nilai ? number_format($nilai->nilai_uts, 0) : '-' }}</td>
                                                <td class="text-center small">{{ $nilai ? number_format($nilai->nilai_uas, 0) : '-' }}</td>
                                                <td class="text-center small bg-light fw-bold">{{ $nilai ? number_format($nilai->nilai_akhir, 1) : '-' }}</td>
                                            @endforeach
                                        @else
                                            <td class="text-center text-muted">Belum ada mata pelajaran</td>
                                        @endif
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
