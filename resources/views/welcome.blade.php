<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>I-Tech | Platform Event & Pelatihan</title>
    
    <!-- Fonts & Icons (Menggunakan Boxicons bawaan Sneat) -->
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" />
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body { font-family: 'Public Sans', sans-serif; background-color: #f5f5f9; }
        .hero-section { background: linear-gradient(135deg, #696cff 0%, #435ebe 100%); color: white; padding: 100px 0; }
        .card-event { border: none; transition: transform 0.3s ease, box-shadow 0.3s ease; border-radius: 0.5rem; overflow: hidden; }
        .card-event:hover { transform: translateY(-5px); box-shadow: 0 1rem 3rem rgba(105, 108, 255, 0.15) !important; }
        .btn-primary { background-color: #696cff; border-color: #696cff; }
        .btn-primary:hover { background-color: #5f61e6; border-color: #5f61e6; }
        .badge-category { background-color: rgba(105, 108, 255, 0.1); color: #696cff; }
        .filter-link.active { background-color: #696cff !important; color: white !important; }
        /* Style tambahan untuk dropdown profil */
        .avatar-initial { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 0.85rem; }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-white bg-white sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary fs-4" href="#">
                <i class="bx bx-code-alt me-1 align-middle"></i>STTI NIIT
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center gap-2">
                    <li class="nav-item"><a class="nav-link active" href="#">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#events">Pelatihan</a></li>
                    
                    <!-- KONDISI PERUBAHAN AUTH MULTI ROLE -->
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <!-- Jika yang Login adalah ADMIN -->
                            <li class="nav-item ms-2">
                                <a class="btn btn-primary btn-sm px-3 d-flex align-items-center gap-1" href="{{ route('dashboard') }}">
                                    <i class="bx bx-box fs-5"></i> Panel Admin
                                </a>
                            </li>
                        @else
                            <!-- Jika yang Login adalah PESERTA -->
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
                                        <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('registrations.history') }}">
                                            <i class="bx bx-time-five fs-5 text-primary"></i> Riwayat Pelatihan
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider opacity-25"></li>
                                    <li>
                                        <!-- Form Logout Laravel Resmi -->
                                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2">
                                                <i class="bx bx-power-off fs-5"></i> Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    @else
                        <li class="nav-item ms-2"><a class="btn btn-outline-primary btn-sm px-3" href="{{ route('login') }}">Masuk</a></li>
                        <li class="nav-item"><a class="btn btn-primary btn-sm px-3" href="{{ route('register') }}">Daftar</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section text-center text-md-start">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 mb-4 mb-md-0">
                    <h1 class="display-5 fw-bold mb-3">Tingkatkan Skill Coding & Inovasi Digital Anda</h1>
                    <p class="lead opacity-75 mb-4">Ikuti berbagai event workshop, bootcamp, dan sertifikasi IT terbaik bersama para instruktur ahli di bidangnya.</p>
                    <a href="#events" class="btn btn-light text-primary btn-lg fw-semibold px-4 shadow">Jelajahi Event</a>
                </div>
                <div class="col-md-6 text-center">
                    <i class="bx bx-laptop display-1 opacity-25" style="font-size: 15rem;"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content Section -->
    <main class="container my-5">
        <div class="row">
            
            <!-- Sidebar Kategori (Filter) -->
            <div class="col-lg-3 mb-4">
                <div class="card border-0 shadow-sm p-3">
                    <h6 class="fw-bold mb-3"><i class="bx bx-grid-alt me-2 text-primary"></i>Kategori Pelatihan</h6>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('landing') }}#events" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center border-0 rounded filter-link {{ !$selectedCategory ? 'active' : '' }}">
                            Semua Kelas
                        </a>
                        @foreach($categories as $cat)
                            <a href="{{ route('landing', ['category' => $cat->id]) }}#events" 
                               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center border-0 rounded mt-1 filter-link {{ $selectedCategory == $cat->id ? 'active' : '' }}">
                                {{ $cat->nama_kategori }}
                                <span class="badge rounded-pill bg-secondary bg-opacity-10 text-dark">{{ $cat->events_count }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Daftar Grid Event -->
            <div class="col-lg-9" id="events">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold mb-0 text-heading">Event Tersedia</h4>
                    <span class="text-muted text-sm">Menampilkan {{ $events->count() }} Pelatihan</span>
                </div>

                <div class="row">
                    @forelse($events as $event)
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 card-event shadow-sm bg-white">
                                <!-- Render Poster Event -->
                                <div class="position-relative">
                                    @if($event->poster)
                                        <img src="{{ asset('storage/' . $event->poster) }}" class="card-img-top" alt="Poster {{ $event->judul }}" style="height: 220px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary bg-opacity-25 d-flex align-items-center justify-content-center" style="height: 220px;">
                                            <i class="bx bx-image text-muted display-4"></i>
                                        </div>
                                    @endif
                                    <span class="badge position-absolute top-0 start-0 m-3 badge-category px-2 py-1.5 fw-semibold fs-7 rounded shadow-sm">
                                        {{ $event->category->nama_kategori ?? 'Umum' }}
                                    </span>
                                </div>

                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title fw-bold text-heading mb-2">{{ $event->judul }}</h5>
                                    <p class="card-text text-muted text-sm flex-grow-1">{{ Str::limit($event->deskripsi, 110) }}</p>
                                    
                                    <hr class="text-muted opacity-25 my-3">
                                    
                                    <!-- Informasi Detail Kelas -->
                                    <div class="row g-2 mb-3 text-muted text-xs">
                                        <div class="col-6"><i class="bx bx-calendar me-1 text-primary"></i>{{ \Carbon\Carbon::parse($event->tanggal)->format('d M Y') }}</div>
                                        <div class="col-6"><i class="bx bx-time me-1 text-primary"></i>{{ \Carbon\Carbon::parse($event->jam)->format('H:i') }} WIB</div>
                                        <div class="col-6"><i class="bx bx-map-pin me-1 text-primary"></i>{{ Str::limit($event->lokasi, 15) }}</div>
                                        <div class="col-6"><i class="bx bx-user me-1 text-primary"></i>{{ $event->instructor->nama ?? 'Tentatif' }}</div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-center mt-auto">
                                        <div class="text-primary fw-bold fs-5">
                                            {{ $event->harga == 0 ? 'GRATIS' : 'Rp '.number_format($event->harga, 0, ',', '.') }}
                                        </div>
                                        @auth
                                            <a href="{{ route('registrations.checkout', $event->id) }}" class="btn btn-sm btn-primary px-3">
                                                Daftar Sekarang
                                            </a>
                                        @endauth

                                        @guest
                                            <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary px-3">
                                                Login untuk Daftar
                                            </a>
                                        @endguest
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <i class="bx bx-calendar-x text-muted display-2 mb-3"></i>
                            <h5 class="text-muted">Belum ada kelas aktif untuk kategori ini.</h5>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-top py-4 mt-5 text-center text-muted">
        <div class="container">
            <p class="mb-0">&copy; 2026 Cita Inovasi Indonesia. Seluruh hak cipta dilindungi.</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const successMessage = "{{ session('success_register') }}";
            const errorMessage = "{{ session('error_register') }}";

            if (successMessage) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil Mendaftar!',
                    text: successMessage,
                    confirmButtonColor: '#696cff'
                });
            }

            if (errorMessage) {
                Swal.fire({
                    icon: 'info',
                    title: 'Info Pendaftaran',
                    text: errorMessage,
                    confirmButtonColor: '#8592a3'
                });
            }
        });
    </script>
</body>
</html>