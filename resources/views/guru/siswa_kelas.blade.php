@extends('layouts.app')

@section('title', 'Data Siswa per Kelas')

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
                <div class="row">
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
                    <div class="col-md-6">
                        <label class="form-label" for="kelas_id">Kelas</label>
                        <select class="form-select" id="kelas_id" name="kelas_id" onchange="this.form.submit()" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelasList as $kelas)
                                <option value="{{ $kelas->id }}" {{ $kelas_id == $kelas->id ? 'selected' : '' }}>
                                    {{ $kelas->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($kelas_id)
        <div class="card">
            <div class="card-header bg-white pb-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-3">Daftar Siswa</h5>
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
                                    <th>Foto</th>
                                    <th>NIS</th>
                                    <th>NISN</th>
                                    <th>Nama Siswa</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Jabatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($siswas as $index => $s)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            @if($s->foto)
                                                <img src="{{ asset('storage/' . $s->foto) }}" alt="Foto" class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                                            @else
                                                <img src="{{ asset('assets/img/avatars/user-default.png') }}" alt="Default" class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                                            @endif
                                        </td>
                                        <td>{{ $s->nis }}</td>
                                        <td>{{ $s->nisn }}</td>
                                        <td><strong>{{ $s->nama_siswa }}</strong></td>
                                        <td>{{ $s->jenis_kelamin }}</td>
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
