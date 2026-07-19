@extends('layouts.admin.app')
 
@section('title', 'Daftar Event')
 
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Manajemen /</span> Event Pelatihan
    </h4>

    @if(session('success'))
    <div class="alert alert-primary alert-dismissible d-flex align-items-center mb-4" role="alert">
        <i class="bx bx-check-circle me-2 font-medium-3"></i>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3 pb-2">
            <div class="d-flex align-items-center gap-3">
                <h5 class="mb-0 fw-semibold">Data Event Pelatihan</h5>
                <a href="{{ route('events.create') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-1">
                    <i class="bx bx-plus fs-5"></i> Tambah Event
                </a>
            </div>
 
            <form action="{{ route('events.index') }}" method="GET" style="width: 280px;">
                <div class="input-group input-group-merge">
                    <span class="input-group-text text-muted"><i class="bx bx-search fs-5"></i></span>
                    <input type="text" name="search" class="form-control form-control-sm border-start-0 ps-0" placeholder="Cari judul event..." value="{{ $search }}">
                </div>
            </form>
        </div>

        <div class="card-body pt-2">
            <div class="table-responsive text-nowrap rounded-3 border">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 70px;">No</th>
                            <th>Poster</th>
                            <th>Judul Event</th>
                            <th>Kategori</th>
                            <th>Kuota</th>
                            <th>Harga</th>
                            <th>Status</th>
                            <th style="width: 140px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($events as $key => $event)
                        <tr>
                            <td class="text-center text-muted">{{ $events->firstItem() + $key }}</td>
                            <td>
                                @if($event->poster)
                                    <img src="{{ asset('storage/' . $event->poster) }}" alt="Poster" class="rounded" style="width: 50px; height: 60px; object-cover: cover;">
                                @else
                                    <span class="badge bg-label-secondary">No Image</span>
                                @endif
                            </td>
                            <td>
                                <span class="fw-semibold text-heading d-block">{{ $event->judul }}</span>
                                <small class="text-muted"><i class="bx bx-calendar me-1"></i>{{ \Carbon\Carbon::parse($event->tanggal)->format('d M Y') }}</small>
                            </td>
                            <td><span class="badge bg-label-info">{{ $event->category->nama_kategori ?? 'Uncategorized' }}</span></td>
                            <td><span class="fw-medium">{{ $event->kuota }} Kursi</span></td>
                            <td><strong>Rp {{ number_format($event->harga, 0, ',', '.') }}</strong></td>
                            <td>
                                @if($event->status == 'published')
                                    <span class="badge bg-label-success">Published</span>
                                @elseif($event->status == 'draft')
                                    <span class="badge bg-label-warning">Draft</span>
                                @else
                                    <span class="badge bg-label-danger">Cancelled</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-inline-flex gap-2">
                                    <a href="{{ route('events.edit', $event->id) }}" class="btn btn-sm btn-icon btn-label-warning" data-bs-toggle="tooltip" title="Ubah Event">
                                        <i class="bx bx-edit-alt fs-5"></i>
                                    </a>

                                    <form id="delete-form-{{ $event->id }}" action="{{ route('events.destroy', $event->id) }}" method="POST" class="m-0 inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="button"
                                            onclick="executeDelete('{{ $event->id }}','{{ $event->judul }}')"
                                            class="btn btn-sm btn-icon btn-label-danger">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <span class="text-muted d-block">Belum ada data event pelatihan terdaftar.</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4 d-flex justify-content-center">
                {{ $events->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
window.executeDelete = function(id, judulEvent) {
    Swal.fire({
        title: 'Hapus event ' + judulEvent + '?',
        text: "Data yang dihapus bersifat permanen dari database.",
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