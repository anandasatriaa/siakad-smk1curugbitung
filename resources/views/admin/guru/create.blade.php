@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light"><a href="{{ route('admin.guru.index') }}">Data Guru</a> /</span> Tambah Baru
    </h4>

    <div class="row">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Form Tambah Guru</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.guru.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="nip">NIP</label>
                            <input type="text" class="form-control @error('nip') is-invalid @enderror" id="nip" name="nip" value="{{ old('nip') }}" placeholder="1980xxxxxx" required />
                            @error('nip')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="nama_guru">Nama Lengkap & Gelar</label>
                            <input type="text" class="form-control @error('nama_guru') is-invalid @enderror" id="nama_guru" name="nama_guru" value="{{ old('nama_guru') }}" placeholder="Budi Santoso, S.Kom" required />
                            @error('nama_guru')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="no_telp">No. Telepon / HP</label>
                            <input type="text" class="form-control @error('no_telp') is-invalid @enderror" id="no_telp" name="no_telp" value="{{ old('no_telp') }}" placeholder="0812...." />
                            @error('no_telp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <strong>Info:</strong> Menyimpan data guru juga akan otomatis membuat akun login dengan email format <code>[NIP]@smkn1curugbitung.sch.id</code> dan password default <code>password</code>.
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan Data</button>
                        <a href="{{ route('admin.guru.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
