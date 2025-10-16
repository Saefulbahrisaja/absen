@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div class="d-flex align-items-center">
        <h1 class="h2 mb-0">Jadwal Absen</h1>
        <p class="text-muted ms-3 mb-0 small">Kelola jadwal waktu masuk dan pulang untuk setiap hari kerja.</p>
    </div>
    <nav aria-label="breadcrumb" class="d-none d-md-block">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('setting.jadwal-kerja') }}">Setting</a></li>
            <li class="breadcrumb-item active" aria-current="page">Jam Absen</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <!-- Notifikasi -->
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0 ms-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Form Tambah Jadwal -->
                <form method="POST" action="{{ route('setting.jam-absen.store') }}" class="row g-3">
                    @csrf
                    <div class="col-md-3">
                        <label for="hari" class="form-label">Hari</label>
                        <select id="hari" name="hari" class="form-select" required>
                            <option value="">-- Pilih Hari --</option>
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                            <option value="Sabtu">Sabtu</option>
                            <option value="Minggu">Minggu</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="tipe" class="form-label">Tipe</label>
                        <select id="tipe" name="tipe" class="form-select" required>
                            <option value="">-- Pilih Tipe --</option>
                            <option value="Masuk">Masuk</option>
                            <option value="Pulang">Pulang</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="jam_mulai" class="form-label">Jam Mulai</label>
                        <input type="time" id="jam_mulai" name="jam_mulai" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <label for="jam_selesai" class="form-label">Jam Selesai</label>
                        <input type="time" id="jam_selesai" name="jam_selesai" class="form-control" required>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i data-lucide="plus"></i> Tambah
                        </button>
                    </div>
                </form>
            </div>

            <div class="card-body">
                @php
                    $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                    $jadwalByDay = collect($jadwal)->groupBy('hari');
                @endphp

                <div class="row g-3">
                    @foreach($days as $day)
                        <div class="col-lg-2 col-md-3 col-sm-6">
                            <div class="card h-100">
                                <div class="card-body p-3">
                                    <h5 class="card-title mb-3 text-center text-dark">{{ $day }}</h5>
                                    @if($jadwalByDay->has($day))
                                        <div class="list-group list-group-flush">
                                            @foreach($jadwalByDay[$day] as $j)
                                                <div class="list-group-item px-2 py-2 border-bottom">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="d-flex align-items-center gap-2">
                                                            <span class="badge {{ $j->tipe == 'Masuk' ? 'bg-primary' : 'bg-danger' }}">
                                                                {{ ucfirst($j->tipe) }}
                                                            </span>
                                                            <span class="text-muted small">{{ $j->jam_mulai }} - {{ $j->jam_selesai }}</span>
                                                        </span>
                                                    </div>

                                                    <!-- Form Edit -->
                                                    <form method="POST" action="{{ route('setting.jam-absen.update', $j->id) }}" class="row g-2 mb-2">
                                                        @csrf @method('PUT')
                                                        <input type="hidden" name="hari" value="{{ $j->hari }}">
                                                        <input type="hidden" name="tipe" value="{{ $j->tipe }}">
                                                        <div class="col-6">
                                                            <input type="time" name="jam_mulai" value="{{ $j->jam_mulai }}" class="form-control form-control-sm" required>
                                                        </div>
                                                        <div class="col-6">
                                                            <input type="time" name="jam_selesai" value="{{ $j->jam_selesai }}" class="form-control form-control-sm" required>
                                                        </div>
                                                        <div class="col-12">
                                                            <button type="submit" class="btn btn-outline-success btn-sm">
                                                                <i data-lucide="save"></i> Update
                                                            </button>
                                                        </div>
                                                    </form>

                                                    <!-- Form Hapus -->
                                                    <form method="POST" action="{{ route('setting.jam-absen.delete', $j->id) }}" class="d-inline" onsubmit="return confirm('Hapus jadwal ini?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                                            <i data-lucide="trash-2"></i> Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-3 text-muted">
                                            <i data-lucide="clock" class="mb-2" style="width: 24px; height: 24px; opacity: 0.5;"></i>
                                            <p class="mb-0 small">Tidak ada jadwal</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection