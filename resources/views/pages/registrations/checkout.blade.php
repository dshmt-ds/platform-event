<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Pembayaran | I-Tech</title>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" />
    <style>
        body { font-family: 'Public Sans', sans-serif; background-color: #f5f5f9; min-height: 100vh; display: flex; align-items: center; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card shadow-sm border-0 overflow-hidden">
                <div class="row g-0">
                    
                    <!-- KIRI: Detail Event & Rekening -->
                    <div class="col-md-5 bg-light p-4 border-end">
                        <a href="{{ route('landing') }}" class="text-muted text-decoration-none text-xs"><i class="bx bx-left-arrow-alt me-1"></i>Batal</a>
                        <h5 class="fw-bold text-heading mt-3 mb-1">Detail Pendaftaran</h5>
                        <p class="text-muted text-xs mb-4">Konfirmasi detail kelas pilihan Anda</p>
                        
                        <div class="mb-4">
                            <span class="badge bg-label-primary mb-2">{{ $event->category->nama_kategori ?? 'Umum' }}</span>
                            <h6 class="fw-bold text-dark mb-2">{{ $event->judul }}</h6>
                            <small class="text-muted d-block"><i class="bx bx-calendar me-1"></i>{{ \Carbon\Carbon::parse($event->tanggal)->format('d M Y') }}</small>
                            <small class="text-muted d-block"><i class="bx bx-time me-1"></i>{{ \Carbon\Carbon::parse($event->jam)->format('H:i') }} WIB</small>
                        </div>

                        <hr class="opacity-25">

                        @if($event->harga > 0)
                            <div class="mt-3 p-3 bg-white rounded border border-dashed">
                                <h6 class="fw-bold text-heading mb-2 text-xs text-uppercase tracking-wider"><i class="bx bx-credit-card me-1 text-primary"></i>Metode Pembayaran</h6>
                                <p class="text-sm text-muted mb-1">Silakan transfer tepat sebesar:</p>
                                <h4 class="fw-bold text-primary mb-3">Rp {{ number_format($event->harga, 0, ',', '.') }}</h4>
                                
                                <div class="text-xs text-muted">
                                    <strong>Bank Mandiri:</strong><br>
                                    157-00-123456-7 a.n. STTI NIIT<br>
                                    <hr class="my-1 opacity-25">
                                    <strong>Bank BCA:</strong><br>
                                    802-987-6543 a.n. Cita Inovasi Indonesia
                                </div>
                            </div>
                        @else
                            <div class="alert alert-success d-flex align-items-center mt-3 text-xs" role="alert">
                                <i class="bx bx-check-circle me-2 fs-5"></i>
                                <div>Kelas ini bersifat <strong>GRATIS</strong>. Anda tidak perlu mengunggah bukti pembayaran apa pun.</div>
                            </div>
                        @endif
                    </div>

                    <!-- KANAN: Upload Form -->
                    <div class="col-md-7 p-4 bg-white d-flex flex-column justify-content-center">
                        <form action="{{ route('registrations.payment_store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="event_id" value="{{ $event->id }}">

                            <h4 class="fw-bold text-heading mb-4">Unggah Bukti Transaksi</h4>

                            <div class="mb-3 text-start">
                                <label class="form-label text-muted text-xs">Akun Pendaftar</label>
                                <input type="text" class="form-control bg-light" value="{{ auth()->user()->name }} ({{ auth()->user()->email }})" disabled>
                            </div>

                            @if($event->harga > 0)
                                <div class="mb-4 text-start">
                                    <label class="form-label fw-semibold text-heading">Pilih File Bukti Transfer (JPG/PNG/WEBP)</label>
                                    
                                    <!-- Diubah dari name="bukti_pembayaran" menjadi name="bukti" -->
                                    <input type="file" name="bukti" id="buktiInput" class="form-control @error('bukti') is-invalid @enderror" required>
                                    @error('bukti') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    
                                    <div class="mt-3 text-center">
                                        <img id="previewBukti" src="#" alt="Preview" class="img-fluid rounded border shadow-sm d-none" style="max-height: 200px;">
                                    </div>
                                </div>
                            @endif
                            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                                {{ $event->harga > 0 ? 'Kirim Bukti & Selesaikan Pendaftaran' : 'Konfirmasi Pendaftaran Gratis' }}
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    if(document.getElementById('buktiInput')) {
        document.getElementById('buktiInput').addEventListener('change', function(e) {
            const preview = document.getElementById('previewBukti');
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                }
                reader.readAsDataURL(file);
            }
        });
    }
</script>
</body>
</html>