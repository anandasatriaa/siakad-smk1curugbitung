@extends('layouts.app')

@section('title', 'Input Nilai Siswa')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Akademik /</span> Input Nilai Siswa
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
            <h5 class="mb-0">Pilih Kelas dan Mata Pelajaran</h5>
        </div>
        <div class="card-body">
            <form id="filterForm" action="{{ route('nilai.index') }}" method="GET">
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
                    <div class="col-md-4">
                        <label class="form-label" for="mapel_id">Mata Pelajaran</label>
                        <select class="form-select" id="mapel_id" name="mapel_id" required>
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            @foreach($mapelList as $mapel)
                                <option value="{{ $mapel->id }}" {{ $mapel_id == $mapel->id ? 'selected' : '' }}>
                                    {{ $mapel->nama_mapel }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="periode_id">Periode Akademik</label>
                        <select class="form-select" id="periode_id" name="periode_id" required>
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
        </div>
    </div>

    <!-- Form Nilai -->
    @if($kelas_id && $mapel_id && count($siswas) > 0)
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Daftar Siswa</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('nilai.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="kelas_id" value="{{ $kelas_id }}">
                    <input type="hidden" name="mapel_id" value="{{ $mapel_id }}">
                    <input type="hidden" name="periode_id" value="{{ $periode_id }}">
                    
                    <div class="table-responsive text-nowrap mb-3">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIS</th>
                                    <th>Nama Siswa</th>
                                    <th>Nilai Tugas</th>
                                    <th>Nilai UTS</th>
                                    <th>Nilai UAS</th>
                                    <th>Nilai Akhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($siswas as $index => $s)
                                    @php
                                        $nilai = isset($nilaiExisting[$s->id]) ? $nilaiExisting[$s->id] : null;
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $s->nis }}</td>
                                        <td>{{ $s->nama_siswa }}</td>
                                        <td>
                                            <input type="number" step="0.01" min="0" max="100" name="tugas[{{ $s->id }}]" class="form-control input-nilai tugas" value="{{ $nilai->nilai_tugas ?? '' }}" placeholder="0 - 100" data-id="{{ $s->id }}">
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" min="0" max="100" name="uts[{{ $s->id }}]" class="form-control input-nilai uts" value="{{ $nilai->nilai_uts ?? '' }}" placeholder="0 - 100" data-id="{{ $s->id }}">
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" min="0" max="100" name="uas[{{ $s->id }}]" class="form-control input-nilai uas" value="{{ $nilai->nilai_uas ?? '' }}" placeholder="0 - 100" data-id="{{ $s->id }}">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control bg-light akhir-{{ $s->id }}" value="{{ $nilai->nilai_akhir ?? '' }}" readonly placeholder="Otomatis">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success"><i class="bx bx-save me-1"></i> Simpan Nilai</button>
                    </div>
                </form>
            </div>
        </div>
    @elseif($kelas_id && $mapel_id && count($siswas) == 0)
        <div class="alert alert-warning" role="alert">
            Tidak ada data siswa untuk kelas ini.
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#kelas_id, #mapel_id, #periode_id').on('change', function() {
            let kelas = $('#kelas_id').val();
            let mapel = $('#mapel_id').val();
            let periode = $('#periode_id').val();

            if (kelas && mapel && periode) {
                $('#filterForm').submit();
            }
        });

        function calculateAverage(row) {
            let id = row.find('.tugas').data('id');
            let tugas = row.find('.tugas').val();
            let uts = row.find('.uts').val();
            let uas = row.find('.uas').val();

            if (tugas !== '' || uts !== '' || uas !== '') {
                let t = parseFloat(tugas) || 0;
                let ut = parseFloat(uts) || 0;
                let ua = parseFloat(uas) || 0;
                
                let average = (t + ut + ua) / 3;
                row.find('.akhir-' + id).val(average.toFixed(2));
            } else {
                row.find('.akhir-' + id).val('');
            }
        }

        $('.input-nilai').on('input change', function() {
            calculateAverage($(this).closest('tr'));
        });

        $('.table tbody tr').each(function() {
            calculateAverage($(this));
        });
    });
</script>
@endpush
