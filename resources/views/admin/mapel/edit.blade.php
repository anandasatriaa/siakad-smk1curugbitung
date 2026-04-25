@extends('layouts.app')

@section('title', 'Edit Data Mata Pelajaran')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light"><a href="{{ route('admin.mapel.index') }}">Data Mata Pelajaran</a> /</span> Edit Data
    </h4>

    <div class="row">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Form Edit Mata Pelajaran</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.mapel.update', $mapel->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label" for="kode_mapel">Kode Mapel</label>
                            <input type="text" class="form-control @error('kode_mapel') is-invalid @enderror" id="kode_mapel" name="kode_mapel" value="{{ old('kode_mapel', $mapel->kode_mapel) }}" required />
                            @error('kode_mapel')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="nama_mapel">Nama Mata Pelajaran</label>
                            <input type="text" class="form-control @error('nama_mapel') is-invalid @enderror" id="nama_mapel" name="nama_mapel" value="{{ old('nama_mapel', $mapel->nama_mapel) }}" required />
                            @error('nama_mapel')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Update Data</button>
                        <a href="{{ route('admin.mapel.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
