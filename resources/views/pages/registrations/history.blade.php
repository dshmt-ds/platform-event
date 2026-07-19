<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pelatihan Saya | I-Tech</title>
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" />
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body { font-family: 'Public Sans', sans-serif; background-color: #f5f5f9; min-height: 100vh; }
        .btn-primary { background-color: #696cff; border-color: #696cff; }
        .btn-primary:hover { background-color: #5f61e6; border-color: #5f61e6; }
        .avatar-initial { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 0.85rem; }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-white bg-white sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary fs-4" href="{{ route('landing') }}">
                <i class="bx bx-code-alt me-1 align-middle"></i>STTI NIIT
            </a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center gap-2">
                    <li class="nav-item"><a class="nav-link" href="{{ route('landing') }}">Beranda</a></li>
                    <li class="nav-item dropdown ms-2">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" id="navbarDropdownUser" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="avatar-initial rounded-circle bg-primary text-primary bg-opacity-10">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </div>
                            <span class="fw-medium text-heading">{{ auth()->user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm mt-2" aria-labelledby="navbarDropdownUser">
                            <li>
                                <div class="dropdown-header d-flex flex-column">
                                    <span class="fw-semibold text-heading">{{ auth()->user()->name }}</span>
                                    <small class="text-muted">{{ auth()->user()->email }}</small>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider opacity-25"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="m-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2">
                                        <i class="bx bx-power-off fs-5"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                
                @if(session('info'))
                    <div class="alert alert-info alert-dismissible mb-4" role="alert">
                        <i class="bx bx-info-circle me-2"></i>
                        <div>{{ session('info') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="fw-bold text-heading mb-1">Riwayat Pelatihan Anda</h4>
                        <p class="text-muted text-sm mb-0">Pantau status pendaftaran kelas dan konfirmasi pembayaran Anda di sini.</p>
                    </div>
                    <a href="{{ route('landing') }}" class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1">
                        <i class="bx bx-plus"></i> Tambah Kelas
                    </a>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="table-responsive text-nowrap rounded-3">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Event Pelatihan</th>
                                    <th>Tanggal Daftar</th>
                                    <th>Biaya Investasi</th>
                                    <th class="text-center">Status Pendaftaran</th>
                                    <th class="text-center">Status Pembayaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($registrations as $reg)
                                <tr>
                                    <td>
                                        <span class="fw-bold text-dark d-block">{{ Str::limit($reg->event->judul, 45) }}</span>
                                        <small class="text-muted"><i class="bx bx-calendar me-1"></i>{{ \Carbon\Carbon::parse($reg->event->tanggal)->format('d M Y') }}</small>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($reg->tanggal_daftar)->format('d M Y H:i') }} WIB</td>
                                    <td>
                                        <span class="fw-medium text-dark">
                                            {{ $reg->event->harga == 0 ? 'GRATIS' : 'Rp '.number_format($reg->event->harga, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($reg->status === 'Pending')
                                            <span class="badge bg-warning text-dark px-3 py-2">
                                                <i class="bx bx-time me-1"></i>Pending Review
                                            </span>

                                        @elseif($reg->status === 'Disetujui')
                                            <span class="badge bg-success px-3 py-2">
                                                <i class="bx bx-check-circle me-1"></i>Terdaftar
                                            </span>

                                        @elseif($reg->status === 'Ditolak')
                                            <span class="badge bg-danger px-3 py-2">
                                                <i class="bx bx-x-circle me-1"></i>Ditolak
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($reg->event->harga == 0)
                                            <span class="text-success text-xs fw-semibold"><i class="bx bx-check me-1"></i>Tidak Memerlukan Biaya</span>
                                        @else
                                            @if($reg->payment)
                                                @if($reg->payment->status === 'Pending')
                                                    <span class="text-warning text-xs fw-semibold"><i class="bx bx-loader-alt bx-spin me-1"></i>Menunggu Verifikasi</span>
                                                @elseif($reg->payment->status === 'Disetujui')
                                                    <span class="text-primary text-xs fw-semibold"><i class="bx bx-badge-check me-1"></i>Pembayaran Lunas</span>
                                                @else
                                                    <span class="text-danger text-xs fw-semibold"><i class="bx bx-error me-1"></i>Bukti Ditolak / Gagal</span>
                                                @endif
                                            @else
                                                <span class="text-danger text-xs fw-semibold">Belum Upload</span>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <i class="bx bx-calendar-alt text-muted display-3 mb-3 d-block"></i>
                                        <span class="text-muted">Anda belum mendaftar di kelas pelatihan apa pun.</span>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>