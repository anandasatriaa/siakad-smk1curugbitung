@extends('layouts.app')

@section('title', 'Riwayat Absensi Siswa')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Guru /</span> Riwayat Absensi Siswa
    </h4>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Filter Riwayat Absensi</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('guru.absensi.riwayat') }}" method="GET">
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
                        <label class="form-label" for="tanggal_awal">Dari Tanggal</label>
                        <input type="date" class="form-control" id="tanggal_awal" name="tanggal_awal" value="{{ $tanggal_awal }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="tanggal_akhir">Sampai Tanggal</label>
                        <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" value="{{ $tanggal_akhir }}" required>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100"><i class="bx bx-search me-1"></i> Tampilkan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($kelas_id)
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Riwayat Absensi</h5>
            </div>
            <div class="card-body">
                @if(count($absensis) > 0)
                    <div class="table-responsive text-nowrap">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>NIS</th>
                                    <th>Nama Siswa</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($absensis as $index => $ab)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ \Carbon\Carbon::parse($ab->tanggal)->format('d M Y') }}</td>
                                        <td>{{ $ab->siswa->nis }}</td>
                                        <td>{{ $ab->siswa->nama_siswa }}</td>
                                        <td>
                                            @if($ab->status === 'hadir')
                                                <span class="badge bg-label-success">Hadir</span>
                                            @elseif($ab->status === 'sakit')
                                                <span class="badge bg-label-warning">Sakit</span>
                                            @elseif($ab->status === 'izin')
                                                <span class="badge bg-label-info">Izin</span>
                                            @else
                                                <span class="badge bg-label-danger">Alpa</span>
                                            @endif
                                        </td>
                                        <td>{{ $ab->keterangan ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-warning" role="alert">
                        Tidak ada riwayat absensi pada rentang tanggal tersebut.
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection
