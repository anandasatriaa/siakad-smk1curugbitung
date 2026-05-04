@extends('layouts.app')

@section('title', 'Data Guru')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Master Data /</span> Guru</h4>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Data Guru</h5>
                <a href="{{ route('admin.guru.create') }}" class="btn btn-primary"><i class='bx bx-plus me-1'></i>Tambah
                    Guru</a>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table datatable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Foto</th>
                            <th>NIP</th>
                            <th>Nama Guru</th>
                            <th>No Telp</th>
                            <th>Akun Login</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach($gurus as $guru)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if($guru->foto)
                                        <img src="{{ asset('storage/' . $guru->foto) }}" alt="Foto" class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                                    @else
                                        <img src="{{ asset('assets/img/avatars/user-default.png') }}" alt="Default" class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                                    @endif
                                </td>
                                <td>{{ $guru->nip }}</td>
                                <td><strong>{{ $guru->nama_guru }}</strong></td>
                                <td>{{ $guru->no_telp ?? '-' }}</td>
                                <td>
                                    @if ($guru->user)
                                        <span class="badge bg-label-success">{{ $guru->user->email }}</span>
                                    @else
                                        <span class="badge bg-label-warning">Belum Ada</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.guru.edit', $guru->id) }}"
                                            class="btn btn-sm btn-warning"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                                        <form action="{{ route('admin.guru.destroy', $guru->id) }}" method="POST"
                                            class="d-inline form-delete">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger btn-delete">
                                                <i class="bx bx-trash me-1"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function(e) {
                let form = this.closest('form');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2000,
                customClass: {
                    confirmButton: 'btn btn-primary'
                },
                buttonsStyling: false
            });
        @endif
    </script>
@endpush
