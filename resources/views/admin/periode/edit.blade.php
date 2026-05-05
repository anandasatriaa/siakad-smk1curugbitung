@extends('layouts.app')

@section('title', 'Edit Periode Akademik')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light"><a href="{{ route('admin.periode.index') }}">Periode Akademik</a> /</span> Edit Data
    </h4>

    <div class="row">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Form Edit Periode</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.periode.update', $periode->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label" for="tahun_ajaran">Tahun Ajaran</label>
                            <input type="text" class="form-control @error('tahun_ajaran') is-invalid @enderror" 
                                id="tahun_ajaran" name="tahun_ajaran" value="{{ old('tahun_ajaran', $periode->tahun_ajaran) }}" 
                                placeholder="Contoh: 2024/2025" required />
                            @error('tahun_ajaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="semester">Semester</label>
                            <select class="form-select @error('semester') is-invalid @enderror" id="semester" name="semester" required>
                                <option value="Ganjil" {{ old('semester', $periode->semester) == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                                <option value="Genap" {{ old('semester', $periode->semester) == 'Genap' ? 'selected' : '' }}>Genap</option>
                            </select>
                            @error('semester')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_aktif" name="is_aktif" value="1" {{ old('is_aktif', $periode->is_aktif) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_aktif">Periode Aktif</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Update Data</button>
                        <a href="{{ route('admin.periode.index') }}" class="btn btn-outline-secondary"><i class="bx bx-x-circle me-1"></i> Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
