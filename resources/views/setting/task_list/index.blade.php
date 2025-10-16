@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div class="d-flex align-items-center">
        <h1 class="h2 mb-0">Task List Pegawai</h1>
            </div>
    <nav aria-label="breadcrumb" class="d-none d-md-block">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('setting.task-list') }}">Setting</a></li>
            <li class="breadcrumb-item active" aria-current="page">Task List</li>
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

                <!-- Form Filter dan Pencarian -->
                <form method="GET" action="{{ route('setting.task-list') }}" class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Cari Nama Pegawai</label>
                        <div class="input-group">
                            <span class="input-group-text"><i data-lucide="search"></i></span>
                            <input type="text" id="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari nama pegawai...">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="user_id" class="form-label">Filter Pegawai</label>
                        <select id="user_id" name="user_id" class="form-select">
                            <option value="">Semua Pegawai</option>
                            @foreach ($allUsers as $u)
                                <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                                    {{ $u->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i data-lucide="search"></i> Cari
                        </button>
                        <a href="{{ route('setting.task-list') }}" class="btn btn-outline-secondary">
                            <i data-lucide="refresh-cw"></i> Reset
                        </a>
                    </div>
                </form>

                <!-- Form Tambah Tugas -->
                <form method="POST" action="{{ route('setting.task-list.store') }}" class="row g-3">
                    @csrf
                    <div class="col-md-3">
                        <label for="add_user_id" class="form-label">Pilih Pegawai</label>
                        <select id="add_user_id" name="user_id" class="form-select" required>
                            <option value="">Pilih Pegawai</option>
                            @foreach ($allUsers as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="nama_kegiatan" class="form-label">Nama Kegiatan</label>
                        <input type="text" id="nama_kegiatan" name="nama_kegiatan" class="form-control" placeholder="Nama kegiatan" required>
                    </div>
                    <div class="col-md-2">
                        <label for="poin" class="form-label">Poin</label>
                        <input type="number" id="poin" name="poin" class="form-control" placeholder="Poin" min="0" max="100" required>
                    </div>
                    <div class="col-md-2">
                        <label for="status_kegiatan" class="form-label">Status</label>
                        <select id="status_kegiatan" name="status_kegiatan" class="form-select" required>
                            <option value="Aktif">Belum</option>
                            <option value="Selesai">Selesai</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100">
                            <i data-lucide="plus"></i> Tambahkan
                        </button>
                    </div>
                </form>
            </div>

            <div class="card-body">
                @if ($users->count())
                    @php $user = $users->first(); @endphp
                    <div class="row mb-4">
                        <div class="col-12">
                            <h3 class="mb-2 text-primary">Pegawai: {{ $user->name }}</h3>
                            <p class="text-muted mb-0">
                                Total: {{ $user->kegiatan->count() }} tugas |
                                <span class="text-success">Selesai: {{ $user->kegiatan->where('status_kegiatan','Selesai')->count() }}</span> |
                                <span class="text-warning">Belum: {{ $user->kegiatan->where('status_kegiatan','Aktif')->count() }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Tugas Aktif -->
                        <div class="col-md-6">
                            <h4 class="mb-3 text-warning">
                                <i data-lucide="clock" class="me-1"></i> Tugas Aktif
                            </h4>
                            <div class="list-group list-group-flush">
                                @forelse ($user->kegiatan->where('status_kegiatan','Aktif') as $kegiatan)
                                    <div class="list-group-item px-3 py-3 border-bottom bg-light">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <form method="POST" action="{{ route('setting.task-list.update', $kegiatan->id) }}" class="d-inline">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="nama_kegiatan" value="{{ $kegiatan->nama_kegiatan }}">
                                                <input type="hidden" name="poin" value="{{ $kegiatan->poin }}">
                                                <input type="hidden" name="status_kegiatan" value="Selesai">
                                                <button type="submit" class="btn btn-primary btn-sm me-2 rounded-circle" style="width: 32px; height: 32px; flex-shrink: 0;">
                                                    <i data-lucide="check" class="w-4 h-4"></i>
                                                </button>
                                            </form>
                                            <div class="flex-grow-1 ms-2">
                                                <span class="d-block fw-semibold">{{ $kegiatan->nama_kegiatan }}</span>
                                                <small class="text-muted">Poin: {{ $kegiatan->poin }}</small>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-1 mb-2">
                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="toggleEdit({{ $kegiatan->id }})" title="Edit tugas">
                                                <i data-lucide="edit-3"></i> Edit
                                            </button>
                                            <form method="POST" action="{{ route('setting.task-list.delete', $kegiatan->id) }}" class="d-inline" onsubmit="return confirm('Hapus tugas ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus tugas">
                                                    <i data-lucide="trash-2"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                        <div id="edit-form-{{ $kegiatan->id }}" class="collapse mt-2">
                                            <form method="POST" action="{{ route('setting.task-list.update', $kegiatan->id) }}" class="row g-2">
                                                @csrf @method('PUT')
                                                <div class="col-8">
                                                    <input type="text" name="nama_kegiatan" value="{{ $kegiatan->nama_kegiatan }}" class="form-control form-control-sm" placeholder="Nama kegiatan" required>
                                                </div>
                                                <div class="col-2">
                                                    <input type="number" name="poin" value="{{ $kegiatan->poin }}" class="form-control form-control-sm" min="0" max="100" placeholder="Poin" required>
                                                </div>
                                                <div class="col-2">
                                                    <select name="status_kegiatan" class="form-select form-select-sm">
                                                        <option value="Aktif" {{ $kegiatan->status_kegiatan=='Aktif'?'selected':'' }}>Aktif</option>
                                                        <option value="Selesai" {{ $kegiatan->status_kegiatan=='Selesai'?'selected':'' }}>Selesai</option>
                                                    </select>
                                                </div>
                                                <div class="col-12">
                                                    <button type="submit" class="btn btn-success btn-sm">
                                                        <i data-lucide="save"></i> Simpan
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <div class="list-group-item text-center text-muted py-4">
                                        <i data-lucide="clipboard-list" class="mb-2" style="width: 48px; height: 48px; opacity: 0.5;"></i>
                                        <p class="mb-0">Tidak ada tugas aktif.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Tugas Selesai -->
                        <div class="col-md-6">
                            <h4 class="mb-3 text-success">
                                <i data-lucide="check-circle" class="me-1"></i> Tugas Selesai
                            </h4>
                            <div class="list-group list-group-flush">
                                @forelse ($user->kegiatan->where('status_kegiatan','Selesai') as $kegiatan)
                                    <div class="list-group-item px-3 py-3 border-bottom bg-light">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <form method="POST" action="{{ route('setting.task-list.update', $kegiatan->id) }}" class="d-inline">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="nama_kegiatan" value="{{ $kegiatan->nama_kegiatan }}">
                                                <input type="hidden" name="poin" value="{{ $kegiatan->poin }}">
                                                <input type="hidden" name="status_kegiatan" value="Aktif">
                                                <button type="submit" class="btn btn-success btn-sm me-2 rounded-circle" style="width: 32px; height: 32px; flex-shrink: 0;" title="Kembalikan ke aktif">
                                                    <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                                                </button>
                                            </form>
                                            <div class="flex-grow-1 ms-2">
                                                <span class="d-block text-decoration-line-through text-muted">{{ $kegiatan->nama_kegiatan }}</span>
                                                <small class="text-muted">Poin: {{ $kegiatan->poin }}</small>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-1 mb-2">
                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="toggleEdit({{ $kegiatan->id }})" title="Edit tugas">
                                                <i data-lucide="edit-3"></i> Edit
                                            </button>
                                            <form method="POST" action="{{ route('setting.task-list.delete', $kegiatan->id) }}" class="d-inline" onsubmit="return confirm('Hapus tugas ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus tugas">
                                                    <i data-lucide="trash-2"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                        <div id="edit-form-{{ $kegiatan->id }}" class="collapse mt-2">
                                            <form method="POST" action="{{ route('setting.task-list.update', $kegiatan->id) }}" class="row g-2">
                                                @csrf @method('PUT')
                                                <div class="col-8">
                                                    <input type="text" name="nama_kegiatan" value="{{ $kegiatan->nama_kegiatan }}" class="form-control form-control-sm" placeholder="Nama kegiatan" required>
                                                </div>
                                                <div class="col-2">
                                                    <input type="number" name="poin" value="{{ $kegiatan->poin }}" class="form-control form-control-sm" min="0" max="100" placeholder="Poin" required>
                                                </div>
                                                <div class="col-2">
                                                    <select name="status_kegiatan" class="form-select form-select-sm">
                                                        <option value="Aktif" {{ $kegiatan->status_kegiatan=='Aktif'?'selected':'' }}>Aktif</option>
                                                        <option value="Selesai" {{ $kegiatan->status_kegiatan=='Selesai'?'selected':'' }}>Selesai</option>
                                                    </select>
                                                </div>
                                                <div class="col-12">
                                                    <button type="submit" class="btn btn-success btn-sm">
                                                        <i data-lucide="save"></i> Simpan
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <div class="list-group-item text-center text-muted py-4">
                                        <i data-lucide="check-circle" class="mb-2" style="width: 48px; height: 48px; opacity: 0.5;"></i>
                                        <p class="mb-0">Belum ada tugas selesai.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Pagination -->
                    @if($users->hasPages())
                        <nav aria-label="Pagination" class="mt-4">
                            {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </nav>
                    @endif
                @else
                    <div class="text-center py-5 text-muted">
                        <i data-lucide="clipboard-list" class="mb-3" style="width: 64px; height: 64px; opacity: 0.5;"></i>
                        <h5>Tidak ada data ditemukan.</h5>
                        <p>Coba sesuaikan filter pencarian atau tambahkan tugas baru.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function toggleEdit(id) {
    const form = document.getElementById('edit-form-' + id);
    if (form) {
        const bsCollapse = new bootstrap.Collapse(form, { toggle: false });
        bsCollapse.toggle();
    }
}
</script>
@endsection