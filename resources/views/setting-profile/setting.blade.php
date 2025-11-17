@extends('layout.template')

@section('content')
<div class="main-content-inner">
    <div class="row justify-content-center">
        <div class="col-lg-6 mt-5">
            <div class="card shadow rounded-4 border-0">
                <div class="card-body p-4">

                    <h4 class="header-title text-center mb-4">Pengaturan Akun</h4>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Sukses!</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="text-center mb-4">
                        <img src="{{ $user->foto ? asset('uploads/foto/' . $user->foto) : asset('srtdash/assets/images/author/avatar.png') }}"
                             alt="Foto Profil" width="120" height="120" class="rounded-circle mb-3 shadow-sm border border-secondary">
                        <h5 class="mb-0">{{ $user->nama }}</h5>
                        <small class="text-muted">{{ '@' . $user->username }}</small>
                    </div>

                    <form action="{{ route('profile.setting.update') }}" method="POST" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label for="password_lama" class="form-label fw-semibold">Password Lama</label>
                            <input type="password" name="password_lama" id="password_lama"
                                   class="form-control @error('password_lama') is-invalid @enderror" placeholder="Masukkan password lama" required>
                            @error('password_lama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_baru" class="form-label fw-semibold">Password Baru</label>
                            <input type="password" name="password_baru" id="password_baru"
                                   class="form-control @error('password_baru') is-invalid @enderror" placeholder="Masukkan password baru" required>
                            @error('password_baru')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_baru_confirmation" class="form-label fw-semibold">Konfirmasi Password Baru</label>
                            <input type="password" name="password_baru_confirmation" id="password_baru_confirmation"
                                   class="form-control" placeholder="Ulangi password baru" required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 fw-semibold shadow-sm" style="letter-spacing: 0.05em;">
                            Update Password
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
