@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div class="d-flex align-items-center">
        <h1 class="h2 mb-0">
            <i data-lucide="file-text"></i> Laporan Absen
        </h1>
        <p class="text-muted ms-3 mb-0 small">Lihat dan filter data kehadiran karyawan per bulan dengan opsi export.</p>
    </div>
    <nav aria-label="breadcrumb" class="d-none d-md-block">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('laporan.kehadiran') }}">Laporan</a></li>
            <li class="breadcrumb-item active" aria-current="page">Absen</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <!-- Filter Form -->
                <form method="GET" action="{{ route('laporan.kehadiran') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="user_id" class="form-label">Pilih User</label>
                        <select id="user_id" name="user_id" class="form-select">
                            <option value="">Semua User</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                                    {{ $u->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="month" class="form-label">Pilih Bulan</label>
                        <input type="month" id="month" name="month" class="form-control" value="{{ request('month', now()->format('Y-m')) }}">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i data-lucide="search"></i> Tampilkan
                        </button>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <a href="{{ route('laporan.kehadiran.export', request()->all()) }}" class="btn btn-outline-success w-100">
                            <i data-lucide="download"></i> Export Excel
                        </a>
                    </div>
                </form>
            </div>

            <div class="card-body">
                <!-- Data Absen -->
                @if($laporan->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>Nama</th>
                                    <th>Tanggal</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Keluar</th>
                                    <th>Status Masuk</th>
                                    <th>Status Keluar</th>
                                    <th>Telat</th>
                                    <th>Kegiatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($laporan as $row)
                                    <tr>
                                        <td>{{ $row->user->name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($row->tanggal)->translatedFormat('d M Y') }}</td>
                                        <td>{{ $row->jam_masuk ?? '-' }}</td>
                                        <td>{{ $row->jam_keluar ?? '-' }}</td>
                                        <td>{{ $row->status_masuk }}</td>
                                        <td>{{ $row->status_keluar }}</td>
                                        <td>
                                            <span class="badge {{ $row->telat === 'Ya' ? 'bg-danger' : 'bg-success' }}">
                                                {{ $row->telat }}
                                            </span>
                                        </td>
                                        <td>{{ $row->kegiatan }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($laporan->hasPages())
                        <nav aria-label="Pagination" class="mt-3">
                            {{ $laporan->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </nav>
                    @endif
                @else
                    <div class="text-center py-5 text-muted">
                        <i data-lucide="file-text" class="mb-3" style="width: 64px; height: 64px; opacity: 0.5;"></i>
                        <h5>Tidak ada data untuk kriteria yang dipilih.</h5>
                        <p>Coba sesuaikan filter atau pilih bulan yang berbeda.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection