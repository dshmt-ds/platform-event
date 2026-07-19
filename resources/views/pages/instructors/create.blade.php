@extends('layouts.admin.app')
 
@section('title', 'Tambah Instruktur')
 
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Instruktur /</span> Tambah</h4>
 
    <div class="row">
        <div class="mb-4">
            <a href="{{ route('instructors.index') }}" class="btn btn-secondary"><i class="bx bx-arrow-back"></i> Kembali</a>
        </div>

        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('instructors.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" placeholder="Masukkan nama lengkap">
                                @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Alamat Email</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="nama@email.com">
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nomor Telepon</label>
                                <input type="text" name="telepon" class="form-control @error('telepon') is-invalid @enderror" value="{{ old('telepon') }}" placeholder="Contoh: 08123456789">
                                @error('telepon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Bidang Keahlian / Spesialisasi</label>
                                <input type="text" name="keahlian" class="form-control @error('keahlian') is-invalid @enderror" value="{{ old('keahlian') }}" placeholder="Contoh: Web Development / UI Designer">
                                @error('keahlian') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row mb-4 align-items-center">
                            <div class="col-md-6">
                                <label class="form-label">Foto Profil</label>
                                <input type="file" name="foto" id="fotoInput" class="form-control @error('foto') is-invalid @enderror">
                                @error('foto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 text-center pt-3">
                                <img id="previewImage" src="#" alt="Live Preview" class="img-fluid rounded-circle shadow-sm d-none" style="width: 100px; height: 100px; object-fit: cover;">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan Data</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('fotoInput').addEventListener('change', function(e) {
        const preview = document.getElementById('previewImage');
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
</script>
@endpush