@if($laporan->isEmpty())
<div class="text-center py-5 text-muted">
    <i class="bi bi-graph-down fs-1"></i>
    <h6>Tidak ada data KPI untuk bulan ini</h6>
</div>
@else
<div class="row g-4 mb-4">
    @foreach($laporan as $index => $user)
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h6 class="card-title">{{ $user['nama'] }}</h6>
                <p class="mb-1 text-muted small">
                    Total: <strong>{{ $user['total_kegiatan'] }}</strong> kegiatan | 
                    Selesai: <strong>{{ $user['selesai'] }}</strong> |
                    Rata: <strong>{{ $user['rata_poin'] }}</strong>
                </p>
                  'type' => 'pie',
            'data' => array(
                'labels' => array('Selesai', 'Belum'),
                'datasets' => array(
                    array(
                        'data' => array($user['selesai'], $user['total_kegiatan'] - $user['selesai']),
                        'backgroundColor' => array('#198754', '#ffc107')
                    )
                )
            ),
            'options' => array(
                'plugins' => array(
                    'legend' => array('position' => 'bottom')
                )
            )
        )
    )">
</canvas>
            </div>
        </div>
    </div>
    @endforeach
</div>

<nav>
    {{ $laporan->appends(request()->query())->links('pagination::bootstrap-5') }}
</nav>
@endif
