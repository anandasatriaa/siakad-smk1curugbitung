@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Pengaturan /</span> My Profile
    </h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Detail Profil</h5>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible mb-3" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-4 text-center mb-4">
                                @php
                                    $fotoProfil = asset('assets/img/avatars/user-default.png');
                                    if(auth()->user()->role === 'guru' && $guru && $guru->foto) {
                                        $fotoProfil = asset('storage/' . $guru->foto);
                                    } elseif(auth()->user()->role === 'siswa' && $siswa && $siswa->foto) {
                                        $fotoProfil = asset('storage/' . $siswa->foto);
                                    }
                                @endphp
                                <img src="{{ $fotoProfil }}" alt="Profile" class="d-block rounded-circle mx-auto mb-3" style="width: 150px; height: 150px; object-fit: cover;" id="uploadedAvatar" />
                                
                                @if(in_array(auth()->user()->role, ['guru', 'siswa']))
                                    <div class="button-wrapper">
                                        <label for="foto" class="btn btn-primary me-2 mb-4" tabindex="0">
                                            <span class="d-none d-sm-block"><i class="bx bx-camera me-1"></i> Ganti Foto</span>
                                            <i class="bx bx-upload d-block d-sm-none"></i>
                                            <input type="file" id="foto" name="foto" class="account-file-input" hidden accept="image/*" onchange="previewImage(this)" />
                                        </label>
                                        <p class="text-muted mb-0">Hanya JPG/PNG. Maks 2MB</p>
                                        @error('foto')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                @endif
                            </div>
                            
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="mb-3 col-md-12">
                                        <label for="name" class="form-label">Nama Lengkap</label>
                                        <input class="form-control" type="text" id="name" name="name" value="{{ old('name', $user->name) }}" autofocus />
                                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>
                                    <div class="mb-3 col-md-12">
                                        <label for="email" class="form-label">E-mail</label>
                                        <input class="form-control" type="text" id="email" name="email" value="{{ old('email', $user->email) }}" />
                                        @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>

                                    @if($user->role === 'guru' && $guru)
                                        <div class="mb-3 col-md-12">
                                            <label class="form-label">NIP</label>
                                            <input class="form-control" type="text" value="{{ $guru->nip }}" readonly disabled />
                                        </div>
                                    @elseif($user->role === 'siswa' && $siswa)
                                        <div class="mb-3 col-md-12">
                                            <label class="form-label">NIS</label>
                                            <input class="form-control" type="text" value="{{ $siswa->nis }}" readonly disabled />
                                        </div>
                                    @endif

                                    <hr class="my-4">
                                    <h6>Ganti Password</h6>
                                    
                                    <div class="mb-3 col-md-12">
                                        <label for="password" class="form-label">Password Baru</label>
                                        <input class="form-control" type="password" id="password" name="password" placeholder="Biarkan kosong jika tidak ingin mengubah password" />
                                        @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>
                                    <div class="mb-3 col-md-12">
                                        <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                                        <input class="form-control" type="password" id="password_confirmation" name="password_confirmation" />
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <button type="submit" class="btn btn-primary me-2"><i class="bx bx-save me-1"></i> Simpan Perubahan</button>
                                </div>
                            </div>
                        </div>
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
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('uploadedAvatar').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
