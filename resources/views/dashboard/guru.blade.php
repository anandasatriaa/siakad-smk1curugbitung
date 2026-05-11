@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('dashboard') }}" method="GET" class="row align-items-end">
                        <div class="col-md-4">
                            <label for="periode_id" class="form-label fw-bold">Filter Periode Akademik</label>
                            <select name="periode_id" id="periode_id" class="form-select" onchange="this.form.submit()">
                                @foreach($semuaPeriode as $p)
                                    <option value="{{ $p->id }}" {{ optional($aktifPeriode)->id == $p->id ? 'selected' : '' }}>
                                        {{ $p->tahun_ajaran }} - {{ ucfirst($p->semester) }} 
                                        {{ $p->is_aktif ? '(Aktif)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Selamat Datang, {{ auth()->user()->name }}! 🎉</h5>
                            <p class="mb-4">
                                @if($aktifPeriode)
                                    Menampilkan data periode: <span class="fw-bold">{{ $aktifPeriode->tahun_ajaran }} - {{ ucfirst($aktifPeriode->semester) }}</span>
                                @else
                                    <span class="text-danger">Belum ada periode akademik terpilih.</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="card-info">
                            <p class="card-text text-muted mb-1">Tanggung Jawab Wali Kelas</p>
                            <div class="d-flex align-items-end mb-2">
                                <h4 class="card-title mb-0 me-2">{{ $kelasWaliCount }}</h4>
                                <small class="text-muted"> kelas pada periode aktif ini</small>
                            </div>
                        </div>
                        <div class="card-icon">
                            <span class="badge bg-label-success rounded p-2">
                                <i class="bx bx-user-pin fs-3"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- Jadwal Hari Ini -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Jadwal Mengajar Hari Ini ({{ \Carbon\Carbon::now()->isoFormat('dddd') }})</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Kelas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jadwalHariIni as $j)
                                <tr>
                                    <td><i class="bx bx-time me-1"></i> {{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }}</td>
                                    <td><span class="fw-bold">{{ $j->mata_pelajaran->nama_mapel }}</span></td>
                                    <td><span class="badge bg-label-primary">{{ $j->kelas->nama_kelas }}</span></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada jadwal mengajar untuk hari ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
