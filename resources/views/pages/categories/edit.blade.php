@extends('layouts.admin.app')
 
@section('title', 'Edit Kategori')
 
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Kategori /</span> Edit Kategori
    </h4>
 
    <div class="row">
        <div class="mb-4">
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back"></i> Kembali
            </a>
        </div>
 
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('categories.update', $category->id) }}" method="POST">
                        @csrf
                        @method('PUT')
 
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-icon-default-fullname">Nama Kategori</label>
                            
                            <div class="col-sm-10">
                                <div class="input-group input-group-merge">
                                    <span id="basic-icon-default-fullname2" class="input-group-text">
                                        <i class="bx bx-category"></i>
                                    </span>
                                    
                                    <!-- Diubah menggunakan nama_kategori dan class bootstrap asli -->
                                    <input
                                        type="text"
                                        name="nama_kategori"
                                        class="form-control @error('nama_kategori') is-invalid @enderror"
                                        id="basic-icon-default-fullname"
                                        placeholder="Silahkan isi nama kategori"
                                        aria-label="Silahkan isi nama kategori"
                                        aria-describedby="basic-icon-default-fullname2"
                                        value="{{ old('nama_kategori', $category->nama_kategori) }}"
                                    />
                                    
                                    @error('nama_kategori')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
 
                        <div class="row justify-content-end">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection