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
    $jadwalFormatted = $jadwalKerja;
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
        <h1 class="h2 mb-0">Jadwal Kerja</h1>
        <p class="text-muted ms-3 mb-0 small">Kelola jadwal kerja bulanan dengan drag-and-drop dan edit inline.</p>
    </div>
    <nav aria-label="breadcrumb" class="d-none d-md-block">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('setting.jadwal-kerja') }}">Setting</a></li>
            <li class="breadcrumb-item active" aria-current="page">Jadwal Kerja</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <!-- Navigasi Bulan -->
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <a href="{{ route('setting.jadwal-kerja', ['month' => $prevMonth]) }}" class="btn btn-outline-secondary">
                        <i data-lucide="chevron-left"></i> Sebelumnya
                    </a>
                    <h3 class="mb-0 text-center flex-grow-1">{{ $now->translatedFormat('F Y') }}</h3>
                    <a href="{{ route('setting.jadwal-kerja', ['month' => $nextMonth]) }}" class="btn btn-outline-secondary">
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
                <form method="GET" action="{{ route('setting.jadwal-kerja') }}" class="row g-3 mb-3">
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

                <!-- Form Tambah Jadwal -->
                <form method="POST" action="{{ route('setting.jadwal-kerja.store') }}" class="row g-3">
                    @csrf
                    <div class="col-md-4">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="date" id="tanggal" name="tanggal" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="user_id" class="form-label">Pilih User</label>
                        <select id="user_id" name="user_id" class="form-select" required>
                            <option value="">Pilih User</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
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
                            $items = $jadwalFormatted->get($dateStr, collect());
                        @endphp
                        <div class="day dropzone bg-white border p-2" data-tanggal="{{ $dateStr }}" style="min-height: 120px; position: relative;">
                            <strong class="d-block mb-1">{{ $date->day }}</strong>
                            @foreach ($items as $jadwal)
                                @if ($editId == $jadwal->id)
                                    <form method="POST" action="{{ route('setting.jadwal-kerja.update', $jadwal->id) }}" class="mb-1">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="tanggal" value="{{ $jadwal->tanggal }}">
                                        <select name="user_id" class="form-select form-select-sm mb-1">
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}" {{ $user->id == $jadwal->user_id ? 'selected' : '' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="d-flex gap-1">
                                            <button type="submit" class="btn btn-outline-success btn-sm flex-fill">
                                                <i data-lucide="save"></i> Simpan
                                            </button>
                                            <a href="{{ route('setting.jadwal-kerja', ['month' => $now->format('Y-m')]) }}" class="btn btn-outline-danger btn-sm">
                                                <i data-lucide="x"></i> Batal
                                            </a>
                                        </div>
                                    </form>
                                @else
                                    <div class="jadwal-item badge bg-primary text-white mb-1 d-block w-100 text-start p-2 draggable" draggable="true" data-id="{{ $jadwal->id }}" data-tanggal="{{ $jadwal->tanggal }}" style="cursor: grab; font-size: 0.875rem;">
                                        {{ $jadwal->user->name ?? 'Tanpa Nama' }}
                                    </div>
                                    <div class="action-links small d-flex gap-1">
                                        <a href="{{ route('setting.jadwal-kerja', ['edit' => $jadwal->id, 'month' => $now->format('Y-m')]) }}" class="text-primary text-decoration-none">
                                            <i data-lucide="edit-3" class="me-1"></i> Edit
                                        </a>
                                        <form method="POST" action="{{ route('setting.jadwal-kerja.delete', $jadwal->id) }}" class="d-inline" onsubmit="return confirm('Hapus jadwal?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger p-0 border-0">
                                                <i data-lucide="trash-2" class="me-1"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .calendar-grid .dropzone.dragover {
        background-color: #e3f2fd !important;
        transition: background-color 0.2s ease;
    }
    .jadwal-item.draggable:hover {
        opacity: 0.8;
        transform: scale(1.02);
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

@section('scripts')
<script>
document.querySelectorAll('[draggable="true"]').forEach(el => {
    el.addEventListener('dragstart', e => {
        e.dataTransfer.setData('jadwal-id', el.dataset.id);
        e.dataTransfer.setData('tanggal', el.dataset.tanggal);
    });
});

document.querySelectorAll('.dropzone').forEach(zone => {
    zone.addEventListener('dragover', e => {
        e.preventDefault();
        zone.classList.add('dragover');
    });
    zone.addEventListener('dragleave', () => zone.classList.remove('dragover'));
    zone.addEventListener('drop', e => {
        e.preventDefault();
        const jadwalId = e.dataTransfer.getData('jadwal-id');
        const oldTanggal = e.dataTransfer.getData('tanggal');
        const newTanggal = zone.dataset.tanggal;
        zone.classList.remove('dragover');

        if (oldTanggal === newTanggal) return;

        fetch(`{{ url('setting/jadwal-kerja/update-tanggal') }}/${jadwalId}`, {
            method: 'PUT',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
            body: JSON.stringify({ tanggal: newTanggal })
        })
        .then(r => r.json())
        .then(res => { if (!res.success) alert('Gagal memindahkan jadwal'); else location.reload(); })
        .catch(() => alert('Terjadi kesalahan koneksi.'));
    });
});
</script>
@endsection