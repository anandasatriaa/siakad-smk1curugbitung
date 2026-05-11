@extends('layouts.app')

@section('title', 'Dashboard Siswa')

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
                            @if($kelasSiswa)
                                <p class="mb-0">Kelas Pada Periode Ini: <span class="badge bg-label-info">{{ $kelasSiswa->nama_kelas }}</span></p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h6 class="pb-1 mb-4 text-muted">Ringkasan Absensi Anda (Periode Aktif)</h6>
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-12 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="card-info">
                            <p class="card-text text-muted mb-1">Sakit</p>
                            <div class="d-flex align-items-end mb-2">
                                <h4 class="card-title mb-0 me-2">{{ $sakit }}</h4>
                                <small class="text-muted"> hari</small>
                            </div>
                        </div>
                        <div class="card-icon">
                            <span class="badge bg-label-warning rounded p-2">
                                <i class="bx bx-plus-medical fs-3"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-12 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="card-info">
                            <p class="card-text text-muted mb-1">Izin</p>
                            <div class="d-flex align-items-end mb-2">
                                <h4 class="card-title mb-0 me-2">{{ $izin }}</h4>
                                <small class="text-muted"> hari</small>
                            </div>
                        </div>
                        <div class="card-icon">
                            <span class="badge bg-label-info rounded p-2">
                                <i class="bx bx-envelope fs-3"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-12 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="card-info">
                            <p class="card-text text-muted mb-1">Alpa</p>
                            <div class="d-flex align-items-end mb-2">
                                <h4 class="card-title mb-0 me-2">{{ $alpa }}</h4>
                                <small class="text-muted"> hari</small>
                            </div>
                        </div>
                        <div class="card-icon">
                            <span class="badge bg-label-danger rounded p-2">
                                <i class="bx bx-x-circle fs-3"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <div class="row">
        <div class="col-lg-4 col-md-12 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="card-info">
                            <p class="card-text text-muted mb-1">Rata-rata Nilai Anda</p>
                            <div class="d-flex align-items-end mb-2">
                                <h4 class="card-title mb-0 me-2">{{ number_format($rataRataNilai, 2) }}</h4>
                                <small class="text-muted">/ 100</small>
                            </div>
                        </div>
                        <div class="card-icon">
                            <span class="badge bg-label-success rounded p-2">
                                <i class="bx bx-medal fs-3"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8 col-md-12 mb-4">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar flex-shrink-0 me-3">
                        <span class="avatar-initial rounded bg-label-info"><i class="bx bx-user-voice fs-3"></i></span>
                    </div>
                    <div>
                        <h6 class="mb-0">Wali Kelas Anda</h6>
                        <small class="text-muted">
                            @if($kelasSiswa && $kelasSiswa->wali_kelas)
                                {{ $kelasSiswa->wali_kelas->nama_guru }} ({{ $kelasSiswa->wali_kelas->no_telp ?? 'No Telp Tidak Ada' }})
                            @else
                                Belum Ditentukan
                            @endif
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Jadwal Hari Ini -->
        <div class="col-md-6 col-12 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Jadwal Hari Ini ({{ \Carbon\Carbon::now()->isoFormat('dddd') }})</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>Mapel</th>
                                    <th>Guru</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jadwalHariIni as $j)
                                <tr>
                                    <td>{{ substr($j->jam_mulai, 0, 5) }}</td>
                                    <td><span class="fw-bold">{{ $j->mata_pelajaran->nama_mapel }}</span></td>
                                    <td><small>{{ $j->guru->nama_guru }}</small></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada jadwal hari ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Nilai Terbaru -->
        <div class="col-md-6 col-12 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Nilai Terbaru</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive text-nowrap">
                        <table class="table table-flush">
                            <thead>
                                <tr>
                                    <th>Mata Pelajaran</th>
                                    <th>Nilai Akhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($nilaiTerbaru as $n)
                                <tr>
                                    <td>{{ $n->mataPelajaran->nama_mapel }}</td>
                                    <td><span class="badge bg-label-primary">{{ $n->nilai_akhir }}</span></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="text-center p-3">Belum ada nilai diinput.</td>
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
