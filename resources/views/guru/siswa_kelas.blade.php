@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Guru /</span> Data Siswa per Kelas
    </h4>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Pilih Kelas yang Diajar</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('guru.siswa.kelas') }}" method="GET">
                <div class="row gx-3 gy-2 align-items-end">
                    <div class="col-md-4">
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
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100"><i class="bx bx-search me-1"></i> Tampilkan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($kelas_id)
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Daftar Siswa</h5>
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
                                    <th>Jabatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($siswas as $index => $s)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $s->nis }}</td>
                                        <td>{{ $s->nama_siswa }}</td>
                                        <td>{{ $s->jabatan ? ucfirst($s->jabatan) : 'Siswa' }}</td>
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
