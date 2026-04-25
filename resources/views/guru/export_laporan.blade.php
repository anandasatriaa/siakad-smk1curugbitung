@extends('layouts.app')

@section('title', 'Export Laporan (Raport)')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Guru /</span> Export Laporan (Raport)
    </h4>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Pilih Kelas Wali dan Semester</h5>
        </div>
        <div class="card-body">
            @if(count($kelasList) == 0 && auth()->user()->role !== 'superadmin')
                <div class="alert alert-info">
                    Menu export raport diperuntukkan bagi <strong>Wali Kelas</strong>. Anda saat ini tidak terdaftar sebagai wali kelas untuk kelas manapun.
                </div>
            @else
                <form action="{{ route('guru.laporan.export') }}" method="GET">
                    <div class="row gx-3 gy-2 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label" for="kelas_id">Kelas Wali</label>
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
                                    $endYear = max($startYear, (int)date('Y') + 5);
                                @endphp
                                @for($y = $startYear; $y <= $endYear; $y++)
                                    @php $ta = $y . '/' . ($y + 1); @endphp
                                    <option value="{{ $ta }}" {{ $tahun_ajaran == $ta ? 'selected' : '' }}>{{ $ta }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100"><i class="bx bx-search me-1"></i> Tampilkan Siswa</button>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>

    @if($kelas_id)
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Daftar Siswa untuk Dicetak Raportnya</h5>
            </div>
            <div class="card-body">
                @if(count($siswas) > 0)
                    <div class="table-responsive text-nowrap">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIS</th>
                                    <th>Nama Siswa</th>
                                    <th>Aksi Cetak</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($siswas as $index => $s)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $s->nis }}</td>
                                        <td>{{ $s->nama_siswa }}</td>
                                        <td>
                                            <a href="{{ route('guru.laporan.download', ['siswa_id' => $s->id, 'semester' => $semester, 'tahun_ajaran' => $tahun_ajaran]) }}" target="_blank" class="btn btn-sm btn-danger">
                                                <i class="bx bxs-file-pdf me-1"></i> Cetak Raport PDF
                                            </a>
                                        </td>
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
