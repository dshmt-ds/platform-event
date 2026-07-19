@extends('layouts.admin.app')
 
@section('title', 'Daftar Instruktur')
 
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Manajemen /</span> Instruktur</h4>

    @if(session('success'))
    <div class="alert alert-primary alert-dismissible d-flex align-items-center mb-4" role="alert">
        <i class="bx bx-check-circle me-2"></i>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible d-flex align-items-center mb-4" role="alert">
        <i class="bx bx-error-circle me-2"></i>
        <div>{{ session('error') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
 
    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3 pb-2">
            <div class="d-flex align-items-center gap-3">
                <h5 class="mb-0 fw-semibold">Master Data Instruktur</h5>
                <a href="{{ route('instructors.create') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-1">
                    <i class="bx bx-plus fs-5"></i> Tambah Instruktur
                </a>
            </div>
 
            <form action="{{ route('instructors.index') }}" method="GET" style="width: 280px;">
                <div class="input-group input-group-merge">
                    <span class="input-group-text text-muted"><i class="bx bx-search fs-5"></i></span>
                    <input type="text" name="search" class="form-control form-control-sm border-start-0 ps-0" placeholder="Cari instruktur..." value="{{ $search }}">
                </div>
            </form>
        </div>

        <div class="card-body pt-2">
            <div class="table-responsive text-nowrap rounded-3 border">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 80px;">No</th>
                            <th>Profil</th>
                            <th>Nama Lengkap</th>
                            <th>Kontak</th>
                            <th>Keahlian</th>
                            <th style="width: 140px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($instructors as $key => $inst)
                        <tr>
                            <td class="text-center text-muted">{{ $instructors->firstItem() + $key }}</td>
                            <td>
                                <div class="avatar avatar-md">
                                    @if($inst->foto)
                                        <img src="{{ asset('storage/' . $inst->foto) }}" alt="Foto Profil" class="rounded-circle" style="object-fit: cover;">
                                    @else
                                        <div class="avatar-initial rounded-circle bg-label-primary d-flex align-items-center justify-content-center">
                                            {{ strtoupper(substr($inst->nama, 0, 2)) }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td><strong class="text-heading">{{ $inst->nama }}</strong></td>
                            <td>
                                <span class="d-block text-lowercase text-muted mb-1"><i class="bx bx-envelope me-1"></i>{{ $inst->email }}</span>
                                <span class="d-block text-muted"><i class="bx bx-phone me-1"></i>{{ $inst->telepon }}</span>
                            </td>
                            <td><span class="badge bg-label-primary">{{ $inst->keahlian }}</span></td>
                            <td class="text-center">
                                <div class="d-inline-flex gap-2">
                                    <a href="{{ route('instructors.edit', $inst->id) }}" class="btn btn-sm btn-icon btn-label-warning" data-bs-toggle="tooltip" title="Ubah Instruktur">
                                        <i class="bx bx-edit-alt fs-5"></i>
                                    </a>
         
                                    <form id="delete-form-{{ $inst->id }}" action="{{ route('instructors.destroy', $inst->id) }}" method="POST" class="d-inline m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="button"
                                            onclick="executeDelete('{{ $inst->id }}','{{ $inst->nama }}')"
                                            class="btn btn-sm btn-icon btn-label-danger">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <span class="text-muted d-block">Belum ada data instruktur yang terdaftar.</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4 d-flex justify-content-center">
                {{ $instructors->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
 
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
window.executeDelete = function(id, namaInstruktur) {
    Swal.fire({
        title: 'Hapus instruktur ' + namaInstruktur + '?',
        text: "Data instruktur ini akan dihapus permanen dari sistem.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        customClass: { confirmButton: 'btn btn-danger me-3', cancelButton: 'btn btn-label-secondary' },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            var form = document.getElementById('delete-form-' + id);
            if (form) form.submit();
        }
    });
};
</script>
@endpush