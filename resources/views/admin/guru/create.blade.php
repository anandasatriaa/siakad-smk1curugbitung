@extends('layouts.app')

@section('title', 'Tambah Data Guru')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light"><a href="{{ route('admin.guru.index') }}">Data Guru</a> /</span> Tambah Baru
    </h4>

    <div class="row">
        <div class="col-xl-9">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Form Tambah Guru</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.guru.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="nama_guru">Nama Lengkap</label>
                                    <input type="text" class="form-control @error('nama_guru') is-invalid @enderror" id="nama_guru" name="nama_guru" value="{{ old('nama_guru') }}" placeholder="Budi Santoso" required />
                                    @error('nama_guru')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="jenis_kelamin">Jenis Kelamin</label>
                                    <select class="form-select @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin" name="jenis_kelamin" required>
                                        <option value="">-- Pilih Jenis Kelamin --</option>
                                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                                    </select>
                                    @error('jenis_kelamin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="nip">NIP</label>
                                    <input type="text" class="form-control @error('nip') is-invalid @enderror" id="nip" name="nip" value="{{ old('nip') }}" placeholder="1980xxxxxx" />
                                    @error('nip')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="nuptk">NUPTK</label>
                                    <input type="text" class="form-control @error('nuptk') is-invalid @enderror" id="nuptk" name="nuptk" value="{{ old('nuptk') }}" placeholder="Nomor Unik Pendidik dan Tenaga Kependidikan" />
                                    @error('nuptk')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="nik">NIK</label>
                                    <input type="text" class="form-control @error('nik') is-invalid @enderror" id="nik" name="nik" value="{{ old('nik') }}" placeholder="Nomor Induk Kependudukan" />
                                    @error('nik')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="tempat_lahir">Tempat Lahir</label>
                                    <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir') }}" placeholder="Jakarta" />
                                    @error('tempat_lahir')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="tanggal_lahir">Tanggal Lahir</label>
                                    <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" />
                                    @error('tanggal_lahir')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="agama">Agama</label>
                                    <select class="form-select @error('agama') is-invalid @enderror" id="agama" name="agama">
                                        <option value="">-- Pilih Agama --</option>
                                        <option value="Islam" {{ old('agama') == 'Islam' ? 'selected' : '' }}>Islam</option>
                                        <option value="Kristen" {{ old('agama') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                        <option value="Katolik" {{ old('agama') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                        <option value="Hindu" {{ old('agama') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                        <option value="Budha" {{ old('agama') == 'Budha' ? 'selected' : '' }}>Budha</option>
                                        <option value="Konghucu" {{ old('agama') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                                    </select>
                                    @error('agama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="no_telp">No. Telepon / HP</label>
                                    <input type="text" class="form-control @error('no_telp') is-invalid @enderror" id="no_telp" name="no_telp" value="{{ old('no_telp') }}" placeholder="0812xxx" />
                                    @error('no_telp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="status_kepegawaian">Status Kepegawaian</label>
                                    <input class="form-control @error('status_kepegawaian') is-invalid @enderror" list="statusList" id="status_kepegawaian" name="status_kepegawaian" value="{{ old('status_kepegawaian') }}" placeholder="Pilih atau ketik">
                                    <datalist id="statusList">
                                        <option value="PNS">
                                        <option value="PPPK">
                                        <option value="PPPK Paruh Waktu">
                                        <option value="Honor Daerah TK.I Provinsi">
                                    </datalist>
                                    @error('status_kepegawaian')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="jenis_ptk">Jenis PTK</label>
                                    <input class="form-control @error('jenis_ptk') is-invalid @enderror" list="ptkList" id="jenis_ptk" name="jenis_ptk" value="{{ old('jenis_ptk') }}" placeholder="Pilih atau ketik">
                                    <datalist id="ptkList">
                                        <option value="Guru">
                                        <option value="Kepala Sekolah">
                                        <option value="Tenaga Kependidikan">
                                        <option value="Pramubakti">
                                        <option value="Satpam">
                                    </datalist>
                                    @error('jenis_ptk')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="foto">Foto Profil (Opsional)</label>
                            <input type="file" class="form-control @error('foto') is-invalid @enderror" id="foto" name="foto" accept="image/*" onchange="previewImage(this)" />
                            @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="mt-2">
                                <img id="foto_preview" src="#" alt="Preview Foto" class="rounded" width="100" style="display: none; object-fit: cover;">
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <strong>Info:</strong> Menyimpan data guru juga akan otomatis membuat akun login dengan email format <code>[NIP]@smkn1curugbitung.sch.id</code> dan password default <code>smkn1curugbitung</code>.
                        </div>

                        <button type="submit" class="btn btn-primary"> <i class="bx bx-save me-1"></i> Simpan Data</button>
                        <a href="{{ route('admin.guru.index') }}" class="btn btn-outline-secondary"><i class="bx bx-x-circle me-1"></i> Batal</a>
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
