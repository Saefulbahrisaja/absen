<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HariLibur;
use App\Models\JadwalAbsen;
use App\Models\jadwalKerja;
use Carbon\Carbon;
use App\Models\User;
use App\Models\KegiatanUser;



class SettingController extends Controller
{
    
    public function jadwalKerja(Request $request)
    {
        $monthParam = $request->input('month');
        $now = $monthParam ? Carbon::parse($monthParam . '-01') : Carbon::now();

        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        // Ambil semua jadwal kerja dengan relasi user
        $rawJadwal = JadwalKerja::with('user')
            ->whereBetween('tanggal', [$startOfMonth->format('Y-m-d'), $endOfMonth->format('Y-m-d')])
            ->orderBy('tanggal', 'asc')
            ->get();

            $users = User::orderBy('name')->get();

        // Untuk tampilan per tanggal di grid kalender
        $groupedJadwal = $rawJadwal->groupBy(function ($item) {
            return Carbon::parse($item->tanggal)->format('Y-m-d');
        });

        // Untuk FullCalendar (opsional)
        $formattedJadwal = $rawJadwal->map(function ($item) {
            return [
                'title' => optional($item->user)->name ?? 'Tidak ada nama',
                'start' => $item->tanggal,
                'color' => '#f87171',
            ];
        });

        return view('setting.jadwal_kerja.index', [
            'jadwalKerja' => $groupedJadwal,
            'formattedJadwal' => $formattedJadwal,
            'now' => $now,
            'users' => $users,
        ]);
    }
    public function storeJadwalKerja(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:user,id',
            'tanggal' => 'required|date',
        ]);
    
        // Cek apakah kombinasi user_id dan tanggal sudah ada
        $exists = JadwalKerja::where('user_id', $request->user_id)
                             ->whereDate('tanggal', $request->tanggal)
                             ->exists();
    
        if ($exists) {
            return back()->with('error', 'User sudah memiliki jadwal kerja pada tanggal tersebut.');
        }
    
        // Simpan data baru
        JadwalKerja::create($request->only('user_id', 'tanggal') + [
            'keterangan' => $request->keterangan,
        ]);
    
        return back()->with('success', 'Jadwal kerja berhasil ditambahkan.');
    }

    public function updateJadwalKerja(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:user,id',
            'tanggal' => 'required|date',
        ]);

        // Cek apakah kombinasi user_id dan tanggal sudah ada di jadwal lain
        $duplicate = JadwalKerja::where('user_id', $request->user_id)
                                ->whereDate('tanggal', $request->tanggal)
                                ->where('id', '!=', $id)
                                ->exists();

        if ($duplicate) {
            return back()->with('error', 'User sudah memiliki jadwal kerja pada tanggal tersebut.');
        }

        // Update data jika tidak duplikat
        $jadwal = JadwalKerja::findOrFail($id);
        $jadwal->update([
            'user_id' => $request->user_id,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
        ]);

        return back()->with('success', 'Jadwal kerja berhasil diperbarui.');
    }

    public function deleteJadwalKerja($id)
    {
        JadwalKerja::findOrFail($id)->delete();
        return back()->with('success', 'Jadwal kerja berhasil dihapus.');
    }


    public function hariLibur()
    {
        $hariLibur = HariLibur::orderBy('tanggal', 'asc')->get();

        // Format untuk FullCalendar
        $formattedLibur = $hariLibur->map(function ($item) {
            return [
                'title' => $item->keterangan,
                'start' => $item->tanggal,
                'color' => '#f87171',
            ];
        });

        return view('setting.hari_libur.index', [
            'hariLibur' => $hariLibur,
            'formattedLibur' => $formattedLibur,
        ]);
    }


    public function storeHariLibur(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date|unique:hari_libur,tanggal',
            'keterangan' => 'required|string'
        ]);

        HariLibur::create($request->only('tanggal', 'keterangan'));

        return back()->with('success', 'Hari libur berhasil ditambahkan.');
    }

    public function updateHariLibur(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date|unique:hari_libur,tanggal,' . $id,
            'keterangan' => 'required|string'
        ]);

        HariLibur::findOrFail($id)->update($request->only('tanggal', 'keterangan'));

        return back()->with('success', 'Hari libur berhasil diperbarui.');
    }

    public function deleteHariLibur($id)
    {
        HariLibur::findOrFail($id)->delete();
        return back()->with('success', 'Hari libur berhasil dihapus.');
    }

    public function deleteJadwalAbsen($id) 
    {
        $jadwal = JadwalAbsen::findOrFail($id);
        $jadwal->delete();
        return back()->with('success', 'Jadwal absen berhasil dihapus.');
        
    }

    public function jamAbsen()
    {
        $jadwal = JadwalAbsen::orderByRaw("FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu')")
                             ->orderBy('tipe')
                             ->get();
    
        return view('setting.jam_absen.index', compact('jadwal'));
    }
    
    public function storeJadwalAbsen(Request $request)
    {
        $request->validate([
            'hari' => 'required|string',
            'tipe' => 'required|in:Masuk,Pulang',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        // Cegah duplikat jadwal untuk hari & tipe yang sama
        $exists = JadwalAbsen::where('hari', $request->hari)
                            ->where('tipe', $request->tipe)
                            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['Duplikat' => 'Jadwal untuk hari dan tipe tersebut sudah ada.'])
                ->withInput();
        }

        try {
            JadwalAbsen::create([
                'hari' => $request->hari,
                'tipe' => $request->tipe,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
            ]);

            return redirect()->back()->with('success', 'Jadwal berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()
                ->withErrors(['Gagal' => 'Gagal menyimpan jadwal. Silakan coba lagi.'])
                ->withInput();
        }
    }
    
    public function updateJadwalAbsen(Request $request, $id)
    {
        $request->validate([
            'hari' => 'required|string',
            'tipe' => 'required|in:Masuk,Pulang',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);
    
        $jadwal = JadwalAbsen::findOrFail($id);
    
        // Cegah update ke duplikat jadwal
        $duplicate = JadwalAbsen::where('id', '!=', $id)
                                ->where('hari', $request->hari)
                                ->where('tipe', $request->tipe)
                                ->exists();
    
        if ($duplicate) {
            return back()->withErrors(['Jadwal dengan hari dan tipe tersebut sudah ada.'])->withInput();
        }
    
        $jadwal->update([
            'hari' => $request->hari,
            'tipe' => $request->tipe,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
        ]);
    
        return back()->with('success', 'Jadwal absen berhasil diperbarui.');
    }

    public function taskList(Request $request)
{
    $search = $request->search;
    $userId = $request->user_id;

    // Ambil daftar user (1 user per halaman)
    $users = User::when($userId, function ($q) use ($userId) {
            $q->where('id', $userId);
        })
        ->when($search, function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%");
        })
        ->paginate(1)
        ->appends($request->query());

    $allUsers = User::orderBy('name')->get();

    // Siapkan variabel kosong default
    $user = $users->first();
    $tugasAktif = collect();
    $tugasSelesai = collect();

    // Jika ada user di halaman saat ini, ambil kegiatan-nya
    if ($user) {
        $tugasAktif = KegiatanUser::where('user_id', $user->id)
            ->where('status_kegiatan', 'Aktif')
            ->orderByDesc('id')
            ->paginate(3, ['*'], 'aktif_page')
            ->appends($request->query());

        $tugasSelesai = KegiatanUser::where('user_id', $user->id)
            ->where('status_kegiatan', 'Selesai')
            ->orderByDesc('id')
            ->paginate(3, ['*'], 'selesai_page')
            ->appends($request->query());
    }

    return view('setting.task_list.index', compact(
        'users',
        'user',
        'tugasAktif',
        'tugasSelesai',
        'allUsers'
    ));
}

    public function storeTaskList(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:user,id',
            'nama_kegiatan' => 'required|string|max:255',
            'status_kegiatan' => 'required|in:Aktif,Selesai', // <- disesuaikan dengan <select> di Blade
            'poin' => 'required|integer|min:0|max:100',
        ]);
    
        // Simpan data baru
        $data = $request->only('user_id', 'nama_kegiatan', 'poin', 'status_kegiatan');
        $data['tanggal_penugasan'] = Carbon::now()->toDateString();
    
        KegiatanUser::create($data);
    
        return back()->with('success', 'Kegiatan berhasil ditambahkan.');
    }
    
    

    public function updateTaskList(Request $request, $id)
    {
        $kegiatan = Kegiatanuser::findOrFail($id);
        $kegiatan->nama_kegiatan = $request->nama_kegiatan;
        $kegiatan->status_kegiatan = $request->status_kegiatan;
        $kegiatan->poin = $request->poin ?? $kegiatan->poin; // optional saat hanya toggle
        $kegiatan->save();

        return back()->with('success', 'Status kegiatan berhasil diperbarui.');
    }


    public function deleteTaskList($id)
    {
        KegiatanUser::findOrFail($id)->delete();
        return back()->with('success', 'Kegiatan berhasil dihapus.');
    }

    public function updateTanggal(Request $request, $id)
    {
        $jadwal = JadwalKerja::findOrFail($id);
        $jadwal->tanggal = $request->tanggal;
        $jadwal->save();

        return response()->json(['success' => true]);
    }
}