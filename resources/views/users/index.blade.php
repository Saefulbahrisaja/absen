@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div class="d-flex align-items-center">
        <h1 class="h2 mb-0">Data User</h1>
        <p class="text-muted ms-3 mb-0 small"></p>
    </div>
    <nav aria-label="breadcrumb" class="d-none d-md-block">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Data User</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <!-- Form Tambah User -->
                <form action="{{ route('users.store') }}" method="POST" class="row g-3 w-100 w-md-auto mb-2 mb-md-0">
                    @csrf
                    <div class="col-md-4">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i data-lucide="plus"></i> Tambah
                        </button>
                    </div>
                </form>

                <!-- Pesan Sukses -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show w-100 w-md-auto" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Form Filter/Search -->
                <form method="GET" action="{{ route('users.index') }}" class="d-flex gap-2 w-100 w-md-auto">
                    <div class="input-group flex-grow-1">
                        <span class="input-group-text"><i data-lucide="search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Cari nama/email..." value="{{ request('search') }}">
                    </div>
                    <button type="submit" class="btn btn-outline-secondary">
                        <i data-lucide="search"></i> Cari
                    </button>
                    @if (request('search'))
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                            <i data-lucide="x"></i> Reset
                        </a>
                    @endif
                </form>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th style="width: 150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr>
                                    <form action="{{ route('users.update', $user) }}" method="POST" class="edit-form">
                                        @csrf
                                        @method('PUT')
                                        <td>
                                            <input type="text" name="name" value="{{ $user->name }}" class="form-control form-control-sm" required>
                                        </td>
                                        <td>
                                            <input type="email" name="email" value="{{ $user->email }}" class="form-control form-control-sm" required>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="submit" class="btn btn-outline-success action-btn save" title="Update">
                                                     <i class="bi bi-check-circle-fill me-1"></i>
                                                </button>
                                            </div>
                                        </form>

                                        <!-- Form Reset -->
                                        <form action="{{ route('users.reset', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Reset akun user ini?')">
                                            @csrf
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="submit" class="btn btn-outline-warning action-btn reset" title="Reset">
                                                    <i class="bi bi-arrow-counterclockwise me-1"></i>
                                                </button>
                                            </div>
                                        </form>

                                        <!-- Form Delete -->
                                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus user ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="submit" class="btn btn-outline-danger action-btn delete" title="Hapus">
                                                    <i class="bi bi-x-octagon-fill me-1"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">
                                        <i data-lucide="users" class="mb-2" style="width: 48px; height: 48px; opacity: 0.5;"></i>
                                        <p>Belum ada pengguna.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                @if($users->hasPages())
                    <nav aria-label="Pagination" class="mt-3">
                        {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </nav>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection