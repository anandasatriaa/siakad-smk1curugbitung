@extends('layouts.app')

@section('title', 'Jadwal Mengajar')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center py-3 mb-2">
        <h4 class="fw-bold mb-0">
            <span class="text-muted fw-light">Guru /</span> Jadwal Mengajar
        </h4>
        @if(auth()->user()->role === 'guru')
            <div class="text-end">
                <span class="badge bg-label-primary p-2 px-3">
                    <i class="bx bx-calendar me-1"></i> Tahun Ajaran {{ date('Y') }}/{{ date('Y') + 1 }}
                </span>
            </div>
        @endif
    </div>

    @php
        $hari_list = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $today = \Carbon\Carbon::now()->locale('id')->dayName;
    @endphp

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-3"><i class="bx bx-list-ul me-2"></i>Agenda Mengajar Mingguan</h5>
                        <div class="mb-3">
                            <input type="text" id="tableSearch" class="form-control form-control-sm" placeholder="Cari jadwal...">
                        </div>
                    </div>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover align-middle mb-0 datatable-basic">
                        <thead class="table-light">
                            <tr>
                                <th width="150">Hari</th>
                                <th width="150">Waktu</th>
                                <th>Kelas</th>
                                <th>Mata Pelajaran</th>
                                @if(auth()->user()->role === 'superadmin')
                                    <th>Guru Pengajar</th>
                                @endif
                                <th width="100">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $currentHari = ''; @endphp
                            @forelse($jadwals as $jadwal)
                                @php
                                    $isToday = strtolower($jadwal->hari) === strtolower($today);
                                @endphp
                                <tr @if($isToday) class="table-primary" style="--bs-table-bg: rgba(105, 108, 255, 0.05)" @endif>
                                    <td>
                                        @if($currentHari !== $jadwal->hari)
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-xs me-2">
                                                    <span class="avatar-initial rounded bg-label-{{ $isToday ? 'primary' : 'secondary' }}">
                                                        <i class="bx bx-calendar-event"></i>
                                                    </span>
                                                </div>
                                                <span class="fw-bold {{ $isToday ? 'text-primary' : '' }}">{{ $jadwal->hari }}</span>
                                            </div>
                                            @php $currentHari = $jadwal->hari; @endphp
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-semibold text-dark">{{ date('H:i', strtotime($jadwal->jam_mulai)) }} - {{ date('H:i', strtotime($jadwal->jam_selesai)) }}</span>
                                            <small class="text-muted">Durasi: {{ round((strtotime($jadwal->jam_selesai) - strtotime($jadwal->jam_mulai)) / 60) }} Menit</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-label-info">
                                            <i class="bx bxs-graduation me-1"></i> {{ $jadwal->kelas->nama_kelas }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <div class="avatar avatar-sm">
                                                    <span class="avatar-initial rounded-circle bg-label-primary">
                                                        {{ strtoupper(substr($jadwal->mata_pelajaran->nama_mapel, 0, 2)) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $jadwal->mata_pelajaran->nama_mapel }}</div>
                                                <small class="text-muted">{{ $jadwal->mata_pelajaran->kode_mapel }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    @if(auth()->user()->role === 'superadmin')
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bx bx-user-circle me-2 text-muted"></i>
                                                <span>{{ $jadwal->guru->nama_guru }}</span>
                                            </div>
                                        </td>
                                    @endif
                                    <td>
                                        @if($isToday)
                                            <span class="badge bg-success">Hari Ini</span>
                                        @else
                                            <span class="badge bg-label-secondary">Terjadwal</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ auth()->user()->role === 'superadmin' ? '6' : '5' }}" class="text-center py-5">
                                        <div class="text-center">
                                            <i class="bx bx-calendar-x display-4 text-muted"></i>
                                            <p class="mt-2 mb-0">Belum ada jadwal mengajar yang ditemukan.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Simple search functionality
        $("#tableSearch").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
@endpush
