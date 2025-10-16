<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Absen;
use App\Models\JadwalAbsen;
use Carbon\Carbon;
use App\Exports\LaporanKehadiranExport;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $users = User::all();

        // Query dasar
        $query = Absen::with('user');

        // Filter user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter bulan
        if ($request->filled('month')) {
            [$year, $month] = explode('-', $request->month);
            $query->whereYear('tanggal', $year)->whereMonth('tanggal', $month);
        }

        // Pagination (10 data per halaman)
        $absensi = $query->orderBy('tanggal', 'desc')->paginate(10);

        // Tambahkan logika telat dan status
        Carbon::setLocale('id');
        foreach ($absensi as $absen) {
            $hari = Carbon::parse($absen->tanggal)->translatedFormat('l');
            $jadwalAbsen = JadwalAbsen::where('hari', $hari)->get();

            $masuk = $jadwalAbsen->where('tipe', 'Masuk')->first();
            $absen->telat = '-';

            if ($masuk && $absen->jam_masuk) {
                $absen->telat = Carbon::parse($masuk->jam_selesai)->lt(Carbon::parse($absen->jam_masuk)) ? 'Ya' : 'Tidak';
            }
        }

        return view('laporan.kehadiran.index', [
            'users' => $users,
            'laporan' => $absensi,
        ]);
    }

    public function exportLaporanKehadiran(Request $request)
    {
        $query = Absen::with('user')
            ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
            ->when($request->month, function ($q) use ($request) {
                [$year, $month] = explode('-', $request->month);
                $q->whereYear('tanggal', $year)->whereMonth('tanggal', $month);
            })
            ->orderBy('tanggal', 'desc');

        $absensi = $query->get();

        return Excel::download(new LaporanKehadiranExport($absensi), 'laporan_kehadiran.xlsx');
    }
}
