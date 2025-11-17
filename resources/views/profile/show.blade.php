@extends('layout.template')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0">
                <div class="row g-0">
                    <!-- Kolom Foto Profil -->
                    <div class="col-md-4 bg-light d-flex flex-column align-items-center justify-content-center p-4">
                        <h5 class="mb-3">Foto Profil</h5>
                        @if($user->foto)
                            <img src="{{ asset('uploads/foto/' . $user->foto) }}" alt="Foto Profil" class="rounded-circle mb-3" width="150" height="150" style="object-fit: cover;">
                        @else
                            <img src="https://via.placeholder.com/150" alt="Default Foto" class="rounded-circle mb-3">
                        @endif

                        <div class="mb-2 w-100">
                            <input type="file" name="foto" id="foto" class="form-control @error('foto') is-invalid @enderror" form="profileForm">
                            @error('foto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <small class="text-muted text-center">Ukuran max 2MB</small>
                    </div>

                    <!-- Kolom Form -->
                    <div class="col-md-8 p-4">
                        <h4 class="mb-4">Edit Profil</h4>

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->nama) }}"
                                    class="form-control @error('name') is-invalid @enderror">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}"
                                    class="form-control @error('username') is-invalid @enderror">
                                @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
