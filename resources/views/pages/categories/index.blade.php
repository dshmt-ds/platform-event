@extends('layouts.admin.app')
 
@section('title', 'Daftar Kategori')
 
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    {{-- Breadcrumb dinamis bawaan Sneat --}}
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Manajemen /</span> Kategori Pelatihan
    </h4>

    {{-- Notifikasi System --}}
    @if(session('success'))
    <div class="alert alert-primary alert-dismissible d-flex align-items-center mb-4" role="alert">
        <i class="bx bx-check-circle me-2 font-medium-3"></i>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible d-flex align-items-center mb-4" role="alert">
        <i class="bx bx-error-circle me-2 font-medium-3"></i>
        <div>{{ session('error') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
 
    <!-- Table Card Container -->
    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3 pb-2">
            <div class="d-flex align-items-center gap-3">
                <h5 class="mb-0 fw-semibold">Master Data Kategori</h5>
                <a href="{{ route('categories.create') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-1">
                    <i class="bx bx-plus fs-5"></i> Tambah Data
                </a>
            </div>
 
            <!-- Form Pencarian -->
            <form action="{{ route('categories.index') }}" method="GET" style="width: 280px;">
                <div class="input-group input-group-merge">
                    <span class="input-group-text text-muted"><i class="bx bx-search fs-5"></i></span>
                    <input
                        type="text" 
                        name="search" 
                        class="form-control form-control-sm border-start-0 ps-0" 
                        placeholder="Cari nama kategori..." 
                        value="{{ $search }}"
                    >
                    @if($search)
                        <a href="{{ route('categories.index') }}" class="input-group-text text-muted bg-transparent border-start-0"><i class="bx bx-x fs-5"></i></a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Tabel Responsif -->
        <div class="card-body pt-2">
            <div class="table-responsive text-nowrap rounded-3 border">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 80px;">No</th>
                            <th>Nama Kategori</th>
                            <th style="width: 140px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $key => $category)
                        <tr>
                            <td class="text-center fw-medium text-muted">
                                {{ $categories->firstItem() + $key }}
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar avatar-xs bg-label-primary rounded p-1 d-flex align-items-center justify-content-center">
                                        <i class="bx bx-folder text-primary fs-5"></i>
                                    </div>
                                    <span class="fw-semibold text-heading">{{ $category->nama_kategori }}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="d-inline-flex gap-2">
                                    <!-- Tombol Edit -->
                                    <a href="{{ route('categories.edit', $category->id) }}" 
                                       class="btn btn-sm btn-icon btn-label-warning" 
                                       data-bs-toggle="tooltip" 
                                       title="Ubah Data">
                                        <i class="bx bx-edit-alt fs-5"></i>
                                    </a>
         
                                    <form id="delete-form-{{ $category->id }}" action="{{ route('categories.destroy', $category->id) }}" method="POST" class="m-0 inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" 
                                                class="btn btn-sm btn-icon btn-label-danger btn-delete-kategori" 
                                                data-id="{{ $category->id }}"
                                                data-nama="{{ $category->nama_kategori }}"
                                                data-bs-toggle="tooltip" 
                                                title="Hapus Data">
                                            <i class="bx bx-trash fs-5" style="pointer-events: none;"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-5">
                                <div class="text-light mb-2"><i class="bx bx-folder-open display-3 text-muted"></i></div>
                                <span class="text-muted d-block fw-medium">Belum ada data kategori yang tersimpan.</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination Wrapper -->
            <div class="mt-4 d-flex justify-content-center">
                {{ $categories->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
 
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    document.querySelectorAll('.btn-delete-kategori').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
        
            const id = this.getAttribute('data-id');
            const namaKategori = this.getAttribute('data-nama');
            
            Swal.fire({
                title: 'Hapus kategori ' + namaKategori + '?',
                text: "Seluruh data event pelatihan yang berkaitan dengan kategori ini berpotensi terganggu.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                customClass: {
                    confirmButton: 'btn btn-danger me-3',
                    cancelButton: 'btn btn-label-secondary'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        });
    });
});
</script>
@endpush