@extends('layouts.app')

@section('title', 'Data Jadwal Pelajaran')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center py-3 mb-4">
            <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Master Data /</span> Jadwal Pelajaran</h4>
            <a href="{{ route('admin.jadwal.create') }}" class="btn btn-primary">
                <i class='bx bx-plus me-1'></i> Tambah Jadwal
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($jadwals->isEmpty())
            <div class="card">
                <div class="card-body text-center">
                    <p class="mb-0">Data jadwal pelajaran masih kosong.</p>
                </div>
            </div>
        @else
            <div class="accordion mt-3" id="accordionJadwal">
                @foreach($jadwals as $nama_kelas => $jadwal_list)
                    @php
                        $collapseId = 'collapse' . Str::slug($nama_kelas);
                    @endphp
                    <div class="card accordion-item mb-3 shadow-none border">
                        <h2 class="accordion-header" id="heading{{ $loop->index }}">
                            <button type="button" class="accordion-button @if(!$loop->first) collapsed @endif" 
                                data-bs-toggle="collapse"
                                data-bs-target="#{{ $collapseId }}" 
                                aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                aria-controls="{{ $collapseId }}">
                                <i class='bx bxs-graduation me-2 text-primary'></i>
                                <span class="fw-bold">KELAS: {{ $nama_kelas }}</span>
                                <span class="badge bg-label-secondary ms-2">{{ $jadwal_list->count() }} Mata Pelajaran</span>
                            </button>
                        </h2>
                        <div id="{{ $collapseId }}" class="accordion-collapse collapse @if($loop->first) show @endif"
                            aria-labelledby="heading{{ $loop->index }}" data-bs-parent="#accordionJadwal">
                            <div class="accordion-body p-0">
                                <div class="table-responsive text-nowrap">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Hari</th>
                                                <th>Waktu</th>
                                                <th>Mata Pelajaran</th>
                                                <th>Guru Pengajar</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $currentHari = '';
                                            @endphp
                                            @foreach($jadwal_list as $jadwal)
                                                <tr>
                                                    <td>
                                                        @if($currentHari !== $jadwal->hari)
                                                            <span class="badge bg-label-info">{{ $jadwal->hari }}</span>
                                                            @php $currentHari = $jadwal->hari; @endphp
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <i class='bx bx-time-five me-1 text-muted'></i>
                                                        {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - 
                                                        {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                                                    </td>
                                                    <td>
                                                        <div class="fw-bold text-primary">{{ $jadwal->mata_pelajaran->nama_mapel ?? '-' }}</div>
                                                        <small class="text-muted">{{ $jadwal->mata_pelajaran->kode_mapel ?? '' }}</small>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <i class='bx bx-user me-2'></i>
                                                            <span>{{ $jadwal->guru->nama_guru ?? '-' }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="dropdown">
                                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                                <i class="bx bx-dots-vertical-rounded"></i>
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item" href="{{ route('admin.jadwal.edit', $jadwal->id) }}">
                                                                    <i class="bx bx-edit-alt me-1"></i> Edit
                                                                </a>
                                                                <form action="{{ route('admin.jadwal.destroy', $jadwal->id) }}" method="POST" class="form-delete">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="button" class="dropdown-item btn-delete">
                                                                        <i class="bx bx-trash me-1"></i> Hapus
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
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
                    customClass: {
                        confirmButton: 'btn btn-danger me-3',
                        cancelButton: 'btn btn-label-secondary'
                    },
                    buttonsStyling: false
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
