@extends('layouts.admin.app')

@section('title', 'Verifikasi Pembayaran')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Manajemen /</span> Verifikasi Pendaftaran</h4>

    @if(session('success'))
    <div class="alert alert-primary alert-dismissible mb-4" role="alert">
        <i class="bx bx-check-circle me-2"></i>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3 pb-2">
            <h5 class="mb-0 fw-semibold">Data Pendaftaran & Transaksi</h5>
            <form action="{{ route('admin.registrations.index') }}" method="GET" style="width: 280px;">
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class="bx bx-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Cari nama atau event..." value="{{ $search }}">
                </div>
            </form>
        </div>

        <div class="card-body pt-2">
            <div class="table-responsive text-nowrap rounded-3 border">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Peserta</th>
                            <th>Event Pelatihan</th>
                            <th>Tanggal Daftar</th>
                            <th>Total Bayar</th>
                            <th>Bukti</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi Konfirmasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($registrations as $reg)
                        <tr>
                            <td>
                                <strong class="text-heading d-block">{{ $reg->user->name }}</strong>
                                <small class="text-muted">{{ $reg->user->email }}</small>
                            </td>
                            <td><span class="fw-medium text-dark">{{ Str::limit($reg->event->judul, 35) }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($reg->tanggal_daftar)->format('d M Y H:i') }}</td>
                            <td>
                                <span class="fw-bold text-primary">
                                    {{ $reg->event->harga == 0 ? 'GRATIS' : 'Rp '.number_format($reg->payment->jumlah ?? $reg->event->harga, 0, ',', '.') }}
                                </span>
                            </td>
                            <td>
                                @if($reg->payment && $reg->payment->bukti)
                                    <button type="button" 
                                            class="btn btn-xs btn-outline-secondary d-flex align-items-center gap-1 btn-lihat-bukti" 
                                            data-bukti="{{ asset('storage/' . $reg->payment->bukti) }}">
                                        <i class="bx bx-image-alt"></i> Lihat
                                    </button>
                                @else
                                    <span class="text-muted text-xs">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($reg->status === 'Pending')
                                    <span class="badge bg-label-warning px-3 py-2">Pending</span>
                                @elseif($reg->status === 'Disetujui')
                                    <span class="badge bg-label-success px-3 py-2">Diterima</span>
                                @else
                                    <span class="badge bg-label-danger px-3 py-2">Ditolak</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($reg->status === 'Pending')
                                    <div class="d-inline-flex gap-2">
                                        <!-- Tombol Setuju -->
                                        <form action="{{ route('admin.registrations.verify', $reg->id) }}" method="POST" class="m-0">
                                            @csrf
                                            <input type="hidden" name="status" value="Diterima">
                                            <button type="submit" class="btn btn-sm btn-success px-2 py-1 d-flex align-items-center gap-1">
                                                <i class="bx bx-check"></i> Setuju
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.registrations.verify', $reg->id) }}" method="POST" class="m-0">
                                            @csrf
                                            <input type="hidden" name="status" value="Ditolak">
                                            <button type="submit" class="btn btn-sm btn-danger px-2 py-1 d-flex align-items-center gap-1">
                                                <i class="bx bx-x"></i> Tolak
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-muted text-xs"><i class="bx bx-lock-alt me-1"></i>Selesai</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Belum ada transaksi pendaftaran masuk.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $registrations->links('pagination::bootstrap-5') }}</div>
        </div>
    </div>
</div>

<div class="modal fade" id="buktiModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header bg-dark text-white">
                <h6 class="modal-title text-white">Lembar Bukti Pembayaran</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-3 bg-light">
                <img id="modalTargetImg" src="" alt="Bukti Transfer" class="img-fluid rounded border shadow-sm" style="max-height: 500px;">
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const buktiModal = new bootstrap.Modal(document.getElementById('buktiModal'));
        const modalTargetImg = document.getElementById('modalTargetImg');

        document.querySelectorAll('.btn-lihat-bukti').forEach(button => {
            button.addEventListener('click', function () {
                const urlBukti = this.getAttribute('data-bukti');
                
                modalTargetImg.src = urlBukti;
                buktiModal.show();
            });
        });
    });
</script>
@endpush