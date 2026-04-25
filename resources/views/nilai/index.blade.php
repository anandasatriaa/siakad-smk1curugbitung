@extends('layouts.app')

@section('title', 'Input Nilai Siswa')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Akademik /</span> Input Nilai Siswa
    </h4>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Form Filter -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Pilih Kelas dan Mata Pelajaran</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('nilai.index') }}" method="GET">
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
                        <label class="form-label" for="mapel_id">Mata Pelajaran</label>
                        <select class="form-select" id="mapel_id" name="mapel_id" required>
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            @foreach($mapelList as $mapel)
                                <option value="{{ $mapel->id }}" {{ $mapel_id == $mapel->id ? 'selected' : '' }}>
                                    {{ $mapel->nama_mapel }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label" for="semester">Semester</label>
                        <select class="form-select" id="semester" name="semester" required>
                            <option value="Ganjil" {{ $semester == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                            <option value="Genap" {{ $semester == 'Genap' ? 'selected' : '' }}>Genap</option>
                        </select>
                    </div>
                    <div class="col-md-2">
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
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100"><i class="bx bx-search me-1"></i> Tampilkan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Form Nilai -->
    @if($kelas_id && $mapel_id && count($siswas) > 0)
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Daftar Siswa</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('nilai.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="kelas_id" value="{{ $kelas_id }}">
                    <input type="hidden" name="mapel_id" value="{{ $mapel_id }}">
                    <input type="hidden" name="semester" value="{{ $semester }}">
                    <input type="hidden" name="tahun_ajaran" value="{{ $tahun_ajaran }}">
                    
                    <div class="table-responsive text-nowrap mb-3">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIS</th>
                                    <th>Nama Siswa</th>
                                    <th>Nilai Akhir (0-100)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($siswas as $index => $s)
                                    @php
                                        $nilai_akhir = isset($nilaiExisting[$s->id]) ? $nilaiExisting[$s->id] : '';
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $s->nis }}</td>
                                        <td>{{ $s->nama_siswa }}</td>
                                        <td>
                                            <input type="number" step="0.01" min="0" max="100" name="nilai[{{ $s->id }}]" class="form-control" value="{{ $nilai_akhir }}" placeholder="Input Nilai">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success"><i class="bx bx-save me-1"></i> Simpan Nilai</button>
                    </div>
                </form>
            </div>
        </div>
    @elseif($kelas_id && $mapel_id && count($siswas) == 0)
        <div class="alert alert-warning" role="alert">
            Tidak ada data siswa untuk kelas ini.
        </div>
    @endif
</div>
@endsection
