@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light"><a href="{{ route('admin.siswa.index') }}">Data Siswa</a> /</span> Edit Data
    </h4>

    <div class="row">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Form Edit Siswa</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.siswa.update', $siswa->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label" for="nis">NIS</label>
                            <input type="text" class="form-control @error('nis') is-invalid @enderror" id="nis" name="nis" value="{{ old('nis', $siswa->nis) }}" required />
                            @error('nis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="nama_siswa">Nama Lengkap</label>
                            <input type="text" class="form-control @error('nama_siswa') is-invalid @enderror" id="nama_siswa" name="nama_siswa" value="{{ old('nama_siswa', $siswa->nama_siswa) }}" required />
                            @error('nama_siswa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="kelas_id">Kelas</label>
                            <select class="form-select @error('kelas_id') is-invalid @enderror" id="kelas_id" name="kelas_id" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($kelases as $kelas)
                                    <option value="{{ $kelas->id }}" {{ old('kelas_id', $siswa->kelas_id) == $kelas->id ? 'selected' : '' }}>{{ $kelas->nama_kelas }}</option>
                                @endforeach
                            </select>
                            @error('kelas_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="jabatan">Jabatan Kelas</label>
                            <select class="form-select @error('jabatan') is-invalid @enderror" id="jabatan" name="jabatan">
                                <option value="" {{ old('jabatan', $siswa->jabatan) == '' ? 'selected' : '' }}>Siswa</option>
                                <option value="ketua_kelas" {{ old('jabatan', $siswa->jabatan) == 'ketua_kelas' ? 'selected' : '' }}>Ketua Kelas</option>
                                <option value="sekretaris" {{ old('jabatan', $siswa->jabatan) == 'sekretaris' ? 'selected' : '' }}>Sekretaris</option>
                                <option value="bendahara" {{ old('jabatan', $siswa->jabatan) == 'bendahara' ? 'selected' : '' }}>Bendahara</option>
                            </select>
                            @error('jabatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="foto">Foto Profil (Kosongkan jika tidak ingin mengubah)</label>
                            @if($siswa->foto)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $siswa->foto) }}" alt="Foto Siswa" class="rounded" width="100">
                                </div>
                            @endif
                            <input type="file" class="form-control @error('foto') is-invalid @enderror" id="foto" name="foto" accept="image/*" onchange="previewImage(this)" />
                            @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="mt-2">
                                <img id="foto_preview" src="#" alt="Preview Foto" class="rounded" width="100" style="display: none; object-fit: cover;">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Data</button>
                        <a href="{{ route('admin.siswa.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewImage(input) {
        var preview = document.getElementById('foto_preview');
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '#';
            preview.style.display = 'none';
        }
    }
</script>
@endpush
