@extends('layouts.app')

@section('title', 'Jadwal Mengajar')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Guru /</span> Jadwal Mengajar
    </h4>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Daftar Jadwal Pelajaran</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Hari</th>
                            <th>Waktu</th>
                            <th>Kelas</th>
                            <th>Mata Pelajaran</th>
                            @if(auth()->user()->role === 'superadmin')
                                <th>Guru Pengajar</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jadwals as $index => $jadwal)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $jadwal->hari }}</td>
                                <td>{{ date('H:i', strtotime($jadwal->jam_mulai)) }} - {{ date('H:i', strtotime($jadwal->jam_selesai)) }}</td>
                                <td>{{ $jadwal->kelas->nama_kelas }}</td>
                                <td>{{ $jadwal->mata_pelajaran->nama_mapel }}</td>
                                @if(auth()->user()->role === 'superadmin')
                                    <td>{{ $jadwal->guru->nama_guru }}</td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->role === 'superadmin' ? '6' : '5' }}" class="text-center text-muted">Belum ada jadwal mengajar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
