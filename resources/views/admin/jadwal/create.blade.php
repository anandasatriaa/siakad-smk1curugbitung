@extends('layouts.app')

@section('title', 'Tambah Data Jadwal')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light"><a href="{{ route('admin.jadwal.index') }}">Data Jadwal</a> /</span> Tambah Baru
        </h4>

        <div class="row">
            <div class="col-xl-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Form Tambah Jadwal Pelajaran</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.jadwal.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="kelas_id">Kelas</label>
                                    <select class="form-select @error('kelas_id') is-invalid @enderror" id="kelas_id"
                                        name="kelas_id" required>
                                        <option value="">-- Pilih Kelas --</option>
                                        @foreach ($kelases as $kelas)
                                            <option value="{{ $kelas->id }}"
                                                {{ old('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                                {{ $kelas->nama_kelas }}</option>
                                        @endforeach
                                    </select>
                                    @error('kelas_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="hari">Hari</label>
                                    <select class="form-select @error('hari') is-invalid @enderror" id="hari"
                                        name="hari" required>
                                        <option value="">-- Pilih Hari --</option>
                                        @foreach ($hari_options as $hari)
                                            <option value="{{ $hari }}"
                                                {{ old('hari') == $hari ? 'selected' : '' }}>{{ $hari }}</option>
                                        @endforeach
                                    </select>
                                    @error('hari')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <hr>
                            <h6>Daftar Mata Pelajaran</h6>
                            <div id="mapel-container">
                                <div class="row mapel-row align-items-end mb-3">
                                    <div class="col-md-3">
                                        <label class="form-label">Mata Pelajaran</label>
                                        <select class="form-select" name="mapel_id[]" required>
                                            <option value="">-- Pilih Mapel --</option>
                                            @foreach ($mapels as $mapel)
                                                <option value="{{ $mapel->id }}">{{ $mapel->nama_mapel }}
                                                    ({{ $mapel->kode_mapel }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Guru Pengajar</label>
                                        <select class="form-select" name="guru_id[]" required>
                                            <option value="">-- Pilih Guru --</option>
                                            @foreach ($gurus as $guru)
                                                <option value="{{ $guru->id }}">{{ $guru->nama_guru }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Jam Mulai</label>
                                        <input type="time" class="form-control" name="jam_mulai[]" required />
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Jam Selesai</label>
                                        <input type="time" class="form-control" name="jam_selesai[]" required />
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger btn-remove-mapel d-none"><i
                                                class="bx bx-trash"></i> Hapus</button>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <button type="button" class="btn btn-success btn-add-mapel"><i class="bx bx-plus"></i>
                                    Tambah Mapel</button>
                            </div>

                            <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
                            <a href="{{ route('admin.jadwal.index') }}" class="btn btn-outline-secondary">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('mapel-container');
            const btnAdd = document.querySelector('.btn-add-mapel');

            btnAdd.addEventListener('click', function() {
                const row = container.querySelector('.mapel-row').cloneNode(true);
                // Clear values
                row.querySelectorAll('select, input').forEach(input => input.value = '');
                // Show remove button
                row.querySelector('.btn-remove-mapel').classList.remove('d-none');
                container.appendChild(row);
            });

            container.addEventListener('click', function(e) {
                if (e.target.closest('.btn-remove-mapel')) {
                    const row = e.target.closest('.mapel-row');
                    if (container.querySelectorAll('.mapel-row').length > 1) {
                        row.remove();
                    }
                }
            });
        });
    </script>
@endpush
