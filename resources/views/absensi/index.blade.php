@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Siswa /</span> Input Absensi Kelas
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
            <h5 class="mb-0">Pilih Kelas dan Tanggal</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('siswa.absensi.index') }}" method="GET">
                <div class="row gx-3 gy-2 align-items-center">
                    <div class="col-md-4">
                        <label class="form-label" for="kelas_id">Kelas</label>
                        <select class="form-select" id="kelas_id" name="kelas_id" {{ !$isSuperadmin ? 'readonly' : '' }}>
                            @if($isSuperadmin)
                                <option value="">-- Pilih Kelas --</option>
                            @endif
                            @foreach($kelasList as $kelas)
                                <option value="{{ $kelas->id }}" {{ $kelas_id == $kelas->id ? 'selected' : '' }}>
                                    {{ $kelas->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="tanggal">Tanggal</label>
                        <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ $tanggal }}" max="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-4 mt-4">
                        <button type="submit" class="btn btn-primary"><i class="bx bx-search me-1"></i> Tampilkan Siswa</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Form Absensi -->
    @if($kelas_id && count($siswas) > 0)
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Daftar Siswa</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('siswa.absensi.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="kelas_id" value="{{ $kelas_id }}">
                    <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                    
                    <div class="table-responsive text-nowrap mb-3">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIS</th>
                                    <th>Nama Siswa</th>
                                    <th>Status Kehadiran</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($siswas as $index => $s)
                                    @php
                                        $existing = isset($absensiExisting[$s->id]) ? $absensiExisting[$s->id] : null;
                                        $status = $existing ? $existing->status : null;
                                        $keterangan = $existing ? $existing->keterangan : '';
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $s->nis }}</td>
                                        <td>{{ $s->nama_siswa }}</td>
                                        <td>
                                            <div class="d-flex gap-3">
                                                <div class="form-check">
                                                    <input name="absensi[{{ $s->id }}][status]" class="form-check-input" type="radio" value="hadir" id="hadir_{{ $s->id }}" {{ $status == 'hadir' ? 'checked' : '' }} required>
                                                    <label class="form-check-label" for="hadir_{{ $s->id }}"> Hadir </label>
                                                </div>
                                                <div class="form-check">
                                                    <input name="absensi[{{ $s->id }}][status]" class="form-check-input" type="radio" value="sakit" id="sakit_{{ $s->id }}" {{ $status == 'sakit' ? 'checked' : '' }} required>
                                                    <label class="form-check-label" for="sakit_{{ $s->id }}"> Sakit </label>
                                                </div>
                                                <div class="form-check">
                                                    <input name="absensi[{{ $s->id }}][status]" class="form-check-input" type="radio" value="izin" id="izin_{{ $s->id }}" {{ $status == 'izin' ? 'checked' : '' }} required>
                                                    <label class="form-check-label" for="izin_{{ $s->id }}"> Izin </label>
                                                </div>
                                                <div class="form-check">
                                                    <input name="absensi[{{ $s->id }}][status]" class="form-check-input" type="radio" value="alpa" id="alpa_{{ $s->id }}" {{ $status == 'alpa' ? 'checked' : '' }} required>
                                                    <label class="form-check-label" for="alpa_{{ $s->id }}"> Alpa </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" name="absensi[{{ $s->id }}][keterangan]" class="form-control form-control-sm" placeholder="Keterangan (Opsional)" value="{{ $keterangan }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success"><i class="bx bx-save me-1"></i>Simpan Absensi</button>
                    </div>
                </form>
            </div>
        </div>
    @elseif($kelas_id && count($siswas) == 0)
        <div class="alert alert-warning" role="alert">
            Tidak ada data siswa untuk kelas ini.
        </div>
    @endif
</div>
@endsection
