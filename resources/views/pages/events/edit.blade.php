@extends('layouts.admin.app')
 
@section('title', 'Edit Event')
 
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Event /</span> Edit Event</h4>
 
    <div class="row">
        <div class="mb-4">
            <a href="{{ route('events.index') }}" class="btn btn-secondary"><i class="bx bx-arrow-back"></i> Kembali</a>
        </div>

        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Judul Event Pelatihan</label>
                                <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul', $event->judul) }}">
                                @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">Kategori</label>
                                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id', $event->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Instruktur</label>
                                <select name="instructor_id" class="form-select @error('instructor_id') is-invalid @enderror">
                                    <option value="">-- Pilih Instruktur --</option>
                                    @foreach($instructors as $inst)
                                        <option value="{{ $inst->id }}" {{ old('instructor_id', $event->instructor_id ?? '') == $inst->id ? 'selected' : '' }}>
                                            {{ $inst->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi Lengkap</label>
                            <textarea name="deskripsi" rows="4" class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $event->deskripsi) }}</textarea>
                            @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Tanggal Pelaksanaan</label>
                                <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', $event->tanggal) }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Jam</label>
                                <input type="time" name="jam" class="form-control @error('jam') is-invalid @enderror" value="{{ old('jam', \Carbon\Carbon::parse($event->jam)->format('H:i')) }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Kuota Peserta</label>
                                <input type="number" name="kuota" class="form-control @error('kuota') is-invalid @enderror" value="{{ old('kuota', $event->kuota) }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Harga Tiket (Rp)</label>
                                <input type="number" name="harga" class="form-control @error('harga') is-invalid @enderror" value="{{ old('harga', $event->harga) }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Lokasi / Tautan Link</label>
                                <input type="text" name="lokasi" class="form-control @error('lokasi') is-invalid @enderror" value="{{ old('lokasi', $event->lokasi) }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Status Penerbitan</label>
                                <select name="status" class="form-select">
                                    <option value="draft" {{ old('status', $event->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status', $event->status) == 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="cancelled" {{ old('status', $event->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Ganti Poster Pelatihan</label>
                                <input type="file" name="poster" id="posterInput" class="form-control">
                                <div class="mt-2">
                                    <img id="previewImage" src="{{ $event->poster ? asset('storage/' . $event->poster) : '#' }}" alt="Preview" class="img-fluid rounded shadow-sm {{ $event->poster ? '' : 'd-none' }}" style="max-height: 150px;">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Perbarui Event</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('posterInput').addEventListener('change', function(e) {
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