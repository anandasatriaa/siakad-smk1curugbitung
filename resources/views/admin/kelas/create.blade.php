@extends('layouts.app')

@section('title', 'Tambah Data Kelas')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light"><a href="{{ route('admin.kelas.index') }}">Data Kelas</a> /</span> Tambah Baru
    </h4>

    <div class="row">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Form Tambah Kelas</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.kelas.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="nama_kelas">Nama Kelas</label>
                            <input type="text" class="form-control @error('nama_kelas') is-invalid @enderror" id="nama_kelas" name="nama_kelas" value="{{ old('nama_kelas') }}" placeholder="10 RPL 1" required />
                            @error('nama_kelas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="wali_kelas_id">Wali Kelas</label>
                            <select class="form-select @error('wali_kelas_id') is-invalid @enderror" id="wali_kelas_id" name="wali_kelas_id">
                                <option value="">-- Tidak Ada / Belum Ditentukan --</option>
                                @foreach($gurus as $guru)
                                    <option value="{{ $guru->id }}" {{ old('wali_kelas_id') == $guru->id ? 'selected' : '' }}>{{ $guru->nama_guru }}</option>
                                @endforeach
                            </select>
                            @error('wali_kelas_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="tahun_ajaran">Tahun Ajaran</label>
                                <select class="form-select @error('tahun_ajaran') is-invalid @enderror" id="tahun_ajaran" name="tahun_ajaran" required>
                                    @foreach($tahun_ajaran_options as $option)
                                        <option value="{{ $option }}" {{ old('tahun_ajaran', date('Y') . '/' . (date('Y') + 1)) == $option ? 'selected' : '' }}>{{ $option }}</option>
                                    @endforeach
                                </select>
                                @error('tahun_ajaran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="semester">Semester</label>
                                <select class="form-select @error('semester') is-invalid @enderror" id="semester" name="semester" required>
                                    <option value="Ganjil" {{ old('semester') == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                                    <option value="Genap" {{ old('semester') == 'Genap' ? 'selected' : '' }}>Genap</option>
                                </select>
                                @error('semester')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Simpan Data</button>
                        <a href="{{ route('admin.kelas.index') }}" class="btn btn-outline-secondary"><i class="bx bx-x-circle me-1"></i> Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
