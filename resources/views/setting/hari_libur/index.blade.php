@extends('layouts.app')

@section('content')
@php
    use Carbon\Carbon;

    $monthParam = request('month');
    $now = $monthParam ? Carbon::parse($monthParam . '-01') : Carbon::now();
    $prevMonth = $now->copy()->subMonth()->format('Y-m');
    $nextMonth = $now->copy()->addMonth()->format('Y-m');

    $startOfMonth = $now->copy()->startOfMonth();
    $endOfMonth = $now->copy()->endOfMonth();
    $startDayOfWeek = $startOfMonth->dayOfWeekIso;

    $hariLiburFormatted = collect($hariLibur)->keyBy(fn($item) => Carbon::parse($item->tanggal)->format('Y-m-d'));
    $editId = request('edit');

    $yearsRange = range(now()->year, now()->year + 5);
    $months = [
        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
        '04' => 'April', '05' => 'Mei', '06' => 'Juni',
        '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
        '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
    ];
@endphp

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div class="d-flex align-items-center">
        <h1 class="h2 mb-0">Hari Libur</h1>
        <p class="text-muted ms-3 mb-0 small">Kelola hari libur bulanan dengan tambah, edit, dan hapus.</p>
    </div>
    <nav aria-label="breadcrumb" class="d-none d-md-block">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('setting.hari-libur') }}">Setting</a></li>
            <li class="breadcrumb-item active" aria-current="page">Hari Libur</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <!-- Navigasi Bulan -->
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <a href="{{ route('setting.hari-libur', ['month' => $prevMonth]) }}" class="btn btn-outline-secondary">
                        <i data-lucide="chevron-left"></i> Sebelumnya
                    </a>
                    <h3 class="mb-0 text-center flex-grow-1">{{ $now->translatedFormat('F Y') }}</h3>
                    <a href="{{ route('setting.hari-libur', ['month' => $nextMonth]) }}" class="btn btn-outline-secondary">
                        Berikutnya <i data-lucide="chevron-right"></i>
                    </a>
                </div>

                <!-- Notifikasi -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Form Filter Bulan -->
                <form method="GET" action="{{ route('setting.hari-libur') }}" class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="month" class="form-label">Pilih Bulan/Tahun</label>
                        <select id="month" name="month" class="form-select">
                            @foreach ($yearsRange as $year)
                                @foreach ($months as $num => $label)
                                    @php $value = $year . '-' . $num; @endphp
                                    <option value="{{ $value }}" {{ $now->format('Y-m') === $value ? 'selected' : '' }}>
                                        {{ $label }} {{ $year }}
                                    </option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i data-lucide="calendar"></i> Tampilkan
                        </button>
                    </div>
                </form>

                <!-- Form Tambah Hari Libur -->
                <form method="POST" action="{{ route('setting.hari-libur.store') }}" class="row g-3">
                    @csrf
                    <div class="col-md-3">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="date" id="tanggal" name="tanggal" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <input type="text" id="keterangan" name="keterangan" class="form-control" placeholder="Keterangan" required>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100">
                            <i data-lucide="plus"></i> Tambah
                        </button>
                    </div>
                </form>
            </div>

            <div class="card-body p-0">
                <!-- Header Hari -->
                <div class="row g-1 text-center fw-bold bg-light border-bottom">
                    @foreach (['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $day)
                        <div class="col border p-2 small text-uppercase">{{ $day }}</div>
                    @endforeach
                </div>

                <!-- Kalender Grid -->
                <div class="calendar-grid" style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 1px; background: #dee2e6;">
                    <!-- Empty cells for start of week -->
                    @for ($i = 1; $i < $startDayOfWeek; $i++)
                        <div class="bg-white border p-2"></div>
                    @endfor

                    @for ($date = $startOfMonth->copy(); $date->lte($endOfMonth); $date->addDay())
                        @php
                            $dateStr = $date->format('Y-m-d');
                            $holiday = $hariLiburFormatted->get($dateStr);
                        @endphp
                        <div class="day bg-white border p-2 text-center" style="min-height: 120px; position: relative;" data-date="{{ $dateStr }}">
                            <strong class="d-block mb-1">{{ $date->day }}</strong>

                            @if($holiday)
                                @if ($editId == $holiday->id)
                                    <form method="POST" action="{{ route('setting.hari-libur.update', $holiday->id) }}" class="mt-1">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="tanggal" value="{{ $holiday->tanggal }}">
                                        <input type="text" name="keterangan" class="form-control form-control-sm mb-2" value="{{ $holiday->keterangan }}" required>
                                        <div class="d-flex gap-1">
                                            <button type="submit" class="btn btn-outline-success btn-sm flex-fill">
                                                <i data-lucide="save"></i> Simpan
                                            </button>
                                            <a href="{{ route('setting.hari-libur', ['month' => $now->format('Y-m')]) }}" class="btn btn-outline-secondary btn-sm">
                                                <i data-lucide="x"></i> Batal
                                            </a>
                                        </div>
                                    </form>
                                @else
                                    <div class="badge bg-warning text-dark mb-1 d-block w-100 p-2" style="font-size: 0.875rem;">
                                        {{ $holiday->keterangan }}
                                    </div>
                                    <div class="action-links small d-flex gap-1 justify-content-center">
                                        <a href="{{ route('setting.hari-libur', ['edit' => $holiday->id, 'month' => $now->format('Y-m')]) }}" class="btn btn-link text-primary p-0">
                                            <i data-lucide="edit-3" class="me-1"></i> Edit
                                        </a>
                                        <form method="POST" action="{{ route('setting.hari-libur.delete', $holiday->id) }}" class="d-inline" onsubmit="return confirm('Hapus hari libur ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger p-0 border-0">
                                                <i data-lucide="trash-2" class="me-1"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .calendar-grid .day:hover {
        background-color: #f8f9fa !important;
        transition: background-color 0.2s ease;
    }
    @media (max-width: 768px) {
        .calendar-grid {
            font-size: 0.75rem;
        }
        .day {
            min-height: 80px;
        }
    }
</style>
@endsection