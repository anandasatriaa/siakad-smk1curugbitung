@extends('layouts.app')

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

                        <button type="submit" class="btn btn-primary">Simpan Data</button>
                        <a href="{{ route('admin.kelas.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
