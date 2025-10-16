@extends('layouts.app')

@section('content')
<style>
/* === Variabel CSS untuk Konsistensi === */
:root {
    --primary: #3b82f6;
    --primary-hover: #2563eb;
    --success: #10b981;
    --success-hover: #059669;
    --warning: #f59e0b;
    --warning-hover: #d97706;
    --bg-light: #f8fafc;
    --border-light: #e2e8f0;
    --text-dark: #1e293b;
    --text-muted: #64748b;
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --border-radius: 0.5rem;
    --transition: all 0.2s ease;
}

/* === Layout dan Komponen Dasar === */
.container {
    max-width: 1200px;
    margin: 1.5rem auto;
    padding: 1.5rem;
    background: #fff;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-md);
    font-family: 'Segoe UI', Tahoma, sans-serif;
}

h1 {
    font-size: 1.75rem;
    color: var(--text-dark);
    margin-bottom: 1.5rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

h2, h3 {
    color: var(--text-dark);
    margin-bottom: 0.75rem;
    font-weight: 600;
}

/* === Form Filter === */
.filter-form {
    display: grid;
    grid-template-columns: 1fr 1fr auto;
    gap: 1rem;
    background: var(--bg-light);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius);
    padding: 1rem;
    margin-bottom: 1.5rem;
    align-items: end;
}

.filter-form label {
    font-size: 0.875rem;
    color: var(--text-muted);
    display: block;
    margin-bottom: 0.25rem;
    font-weight: 500;
}

.filter-form select,
.filter-form input[type="month"] {
    width: 100%;
    padding: 0.5rem 0.75rem;
    border: 1px solid var(--border-light);
    border-radius: 0.375rem;
    font-size: 0.875rem;
    background: white;
    transition: var(--transition);
}

.filter-form select:focus,
.filter-form input[type="month"]:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.filter-form button,
.filter-form .btn {
    padding: 0.625rem 1rem;
    border-radius: 0.375rem;
    border: none;
    font-size: 0.875rem;
    cursor: pointer;
    text-decoration: none;
    font-weight: 500;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    white-space: nowrap;
}

.filter-form button {
    background: var(--primary);
    color: #fff;
}

.filter-form button:hover {
    background: var(--primary-hover);
    transform: translateY(-1px);
}

.filter-form .btn-success {
    background: var(--success);
    color: #fff;
}

.filter-form .btn-success:hover {
    background: var(--success-hover);
    transform: translateY(-1px);
}

/* === Charts Section === */
.charts-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.chart-card {
    background: #fff;
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius);
    padding: 1rem;
    box-shadow: var(--shadow-sm);
}

.chart-card h3 {
    margin-top: 0;
    margin-bottom: 1rem;
    font-size: 1.125rem;
}

.pie-charts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.pie-chart-item {
    text-align: center;
    background: var(--bg-light);
    border-radius: 0.375rem;
    padding: 0.75rem;
}

.pie-chart-item strong {
    display: block;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
    color: var(--text-dark);
}

/* === User Detail Card === */
.user-card {
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius);
    padding: 1rem;
    margin-bottom: 1.5rem;
    box-shadow: var(--shadow-sm);
    background: #fff;
}

.user-card h3 {
    margin-top: 0;
    border-bottom: 2px solid var(--border-light);
    padding-bottom: 0.5rem;
}

.summary-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 0.75rem;
    margin-bottom: 1rem;
    font-size: 0.875rem;
}

.summary-stats p {
    margin: 0;
    padding: 0.5rem;
    background: var(--bg-light);
    border-radius: 0.375rem;
    border-left: 4px solid var(--primary);
}

.summary-stats p:last-child {
    border-left-color: var(--warning);
}

.summary-stats strong {
    color: var(--primary);
    display: block;
}

/* === Tabel Data === */
.table-wrapper {
    overflow-x: auto;
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius);
    margin-top: 1rem;
    box-shadow: var(--shadow-sm);
}

table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.875rem;
}

thead {
    background: #f8fafc;
}

th, td {
    padding: 0.75rem 1rem;
    text-align: left;
    border-bottom: 1px solid var(--border-light);
}

th {
    background: #f1f5f9;
    color: var(--text-dark);
    font-weight: 600;
    white-space: nowrap;
}

tbody tr {
    transition: var(--transition);
}

tbody tr:nth-child(even) {
    background: #f9fafb;
}

tbody tr:hover {
    background: #eff6ff;
}

.bg-green {
    background: #f0fdf4;
    color: var(--success);
    text-align: center;
    font-weight: 600;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
}

.bg-yellow {
    background: #fef3c7;
    color: var(--warning);
    text-align: center;
    font-weight: 600;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
}

/* === No Data State === */
.no-data {
    color: var(--text-muted);
    font-size: 0.875rem;
    text-align: center;
    padding: 2rem;
    font-style: italic;
    background: var(--bg-light);
    border-radius: 0.375rem;
    border: 1px dashed var(--border-light);
}

/* === Pagination === */
.pagination {
    display: flex;
    justify-content: center;
    list-style: none;
    padding: 0;
    margin: 1.5rem 0;
    gap: 0.375rem;
}

.pagination li {
    margin: 0;
}

.pagination a, .pagination span {
    display: block;
    padding: 0.5rem 0.75rem;
    border: 1px solid var(--border-light);
    border-radius: 0.375rem;
    text-decoration: none;
    color: var(--primary);
    font-size: 0.875rem;
    background: white;
    transition: var(--transition);
    min-width: 2.5rem;
    text-align: center;
}

.pagination a:hover {
    background: #f1f5f9;
    border-color: var(--primary);
    color: var(--primary-hover);
}

.pagination .active span {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

.pagination .disabled span {
    color: var(--text-muted);
    background: #f9fafb;
    cursor: not-allowed;
}

/* === Responsif === */
@media (max-width: 768px) {
    .container {
        margin: 1rem;
        padding: 1rem;
    }

    h1 {
        font-size: 1.5rem;
    }

    .filter-form {
        grid-template-columns: 1fr;
        gap: 0.75rem;
    }

    .charts-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .pie-charts-grid {
        grid-template-columns: 1fr;
    }

    .summary-stats {
        grid-template-columns: 1fr;
    }

    .table-wrapper {
        font-size: 0.75rem;
    }

    th, td {
        padding: 0.5rem 0.75rem;
    }

    .bg-green, .bg-yellow {
        padding: 0.125rem 0.25rem;
        font-size: 0.7rem;
    }
}
</style>

<div class="container">
    <h1>
        <i data-lucide="bar-chart-3"></i>
        Laporan Key Performance Index (KPI)
    </h1>

    <!-- Filter Form -->
    <form method="GET" class="filter-form">
        <div>
            <label for="user_id">Pilih User</label>
            <select name="user_id" id="user_id">
                <option value="">Semua data</option>
                @foreach($users as $u)
                    <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                        {{ $u->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="month">Pilih Bulan</label>
            <input type="month" name="month" id="month" value="{{ request('month', now()->format('Y-m')) }}">
        </div>

        <div>
            <button type="submit">
                <i data-lucide="search"></i>
                Tampilkan
            </button>
        </div>
    </form>

    @if($laporan->isEmpty())
        <p class="no-data">Tidak ada data KPI untuk bulan ini.</p>
    @else
        <div class="charts-grid">
            <div class="chart-card">
                <h3>Grafik Total Poin per User</h3>
                <canvas id="kpiChart" height="200"></canvas>
            </div>

            <div class="chart-card">
                <h3>Distribusi Kegiatan per User</h3>
                <div class="pie-charts-grid">
                    @foreach($laporan as $index => $user)
                        <div class="pie-chart-item">
                            <strong>{{ $user['nama'] }}</strong>
                            <canvas id="userChart-{{ $index }}" height="150"></canvas>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Detail KPI per User -->
    @foreach($laporan as $user)
        <div class="user-card">
            <h3>{{ $user['nama'] }}</h3>
            @php
                $total = $user['total_kegiatan'];
                $selesai = $user['selesai'] ?? 0;
                $aktif = $total - $selesai;
                $persenSelesai = $total > 0 ? round(($selesai / $total) * 100, 2) : 0;
                $persenAktif = $total > 0 ? round(($aktif / $total) * 100, 2) : 0;
            @endphp

            <div class="summary-stats">
                <p>Total Kegiatan: <strong>{{ $total }}</strong></p>
                <p>Total Poin: <strong>{{ $user['total_poin'] }}</strong></p>
                <p>Rata-rata Poin: <strong>{{ $user['rata_poin'] }}</strong></p>
                <p>Selesai: <strong>{{ $selesai }}</strong> ({{ $persenSelesai }}%)</p>
                <p>Belum Selesai: <strong>{{ $aktif }}</strong> ({{ $persenAktif }}%)</p>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Kegiatan</th>
                            <th>Poin</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user['data'] as $row)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($row['tanggal_selesai'])->translatedFormat('d M Y') }}</td>
                                <td>{{ $row['nama_kegiatan'] }}</td>
                                <td style="text-align:center;">{{ $row['poin'] }}</td>
                                <td style="text-align:center;">
                                    <span class="{{ $row['status'] === 'Selesai' ? 'bg-green' : 'bg-yellow' }}">
                                        {{ $row['status'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach

    <!-- Pagination -->
    @if ($laporan instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="pagination">
            {{ $laporan->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<!-- ChartJS -->
@if(!$laporan->isEmpty())
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('kpiChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($laporan->pluck('nama')) !!},
            datasets: [{
                label: 'Total Poin',
                data: {!! json_encode($laporan->pluck('total_poin')) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, title: { display: true, text: 'Total Poin' } },
                x: { title: { display: true, text: 'Nama User' } }
            }
        }
    });

    @foreach($laporan as $index => $user)
    new Chart(document.getElementById('userChart-{{ $index }}'), {
        type: 'pie',
        data: {
            labels: ['Selesai', 'Belum Selesai'],
            datasets: [{
                data: [{{ $user['selesai'] ?? 0 }}, {{ ($user['total_kegiatan'] ?? 0) - ($user['selesai'] ?? 0) }}],
                backgroundColor: ['#28a745', '#ffc107']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' },
                title: { display: false }
            }
        }
    });
    @endforeach
</script>
@endif

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Initialize Lucide icons if available
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
});
</script>
@endsection