@extends('layouts.app')

@section('title', 'Export Laporan')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Guru /</span> Export Laporan
    </h4>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Pilih Kelas Wali dan Semester</h5>
        </div>
        <div class="card-body">
            @if(count($kelasList) == 0 && auth()->user()->role !== 'superadmin')
                <div class="alert alert-info">
                    Menu export laporan diperuntukkan bagi <strong>Wali Kelas</strong>. Anda saat ini tidak terdaftar sebagai wali kelas untuk kelas manapun.
                </div>
            @else
                <form action="{{ route('guru.laporan.export') }}" method="GET">
                    <div class="row gx-3 gy-2 align-items-end">
                        <div class="col-md-6">
                            <label class="form-label" for="kelas_id">Kelas Wali</label>
                            <select class="form-select" id="kelas_id" name="kelas_id" onchange="this.form.submit()" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($kelasList as $kelas)
                                    <option value="{{ $kelas->id }}" {{ $kelas_id == $kelas->id ? 'selected' : '' }}>
                                        {{ $kelas->nama_kelas }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="periode_id">Periode Akademik</label>
                            <select class="form-select" id="periode_id" name="periode_id" onchange="this.form.submit()" required>
                                <option value="">-- Pilih Periode --</option>
                                @foreach($periodes as $p)
                                    <option value="{{ $p->id }}" {{ $periode_id == $p->id ? 'selected' : '' }}>
                                        {{ $p->tahun_ajaran }} - {{ $p->semester }} {{ $p->is_aktif ? '(Aktif)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>

    @if($kelas_id)
        <div class="card">
            <div class="card-header bg-white pb-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-3">Daftar Siswa untuk Dicetak Laporannya</h5>
                    <div class="mb-3">
                        <input type="text" id="tableSearch" class="form-control form-control-sm" placeholder="Cari siswa...">
                    </div>
                </div>
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
                                            <a href="{{ route('guru.laporan.download', ['siswa_id' => $s->id, 'periode_id' => $periode_id]) }}" target="_blank" class="btn btn-sm btn-danger">
                                                <i class="bx bxs-file-pdf me-1"></i> Cetak Laporan PDF
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

@push('scripts')
<script>
    $(document).ready(function() {
        $("#tableSearch").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
@endpush
