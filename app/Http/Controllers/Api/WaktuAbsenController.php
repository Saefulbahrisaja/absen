<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Absen;
use App\Models\HariLibur;
use App\Models\JadwalAbsen;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\JadwalKerja;

class WaktuAbsenController extends Controller
{
    public function cek(Request $request)
    {
        $userId = $request->user()->id; 
        $now = Carbon::now();
        $today = $now->toDateString();
        $hariIni = strtolower($now->locale('id')->isoFormat('dddd')); // senin, selasa, dst

        $isLibur = false;
        $jadwal_kerja = false;
        $keteranganLibur = null;
        $status = 'libur';
        $masuk = null;
        $pulang = null;

        // Cek apakah hari ini Sabtu atau Minggu
        // if (in_array($hariIni, ['sabtu', 'minggu'])) {
        //     $isLibur = true;
        //     $keteranganLibur = 'Hari ini adalah hari libur (Sabtu atau Minggu).';
        // }

        // Cek apakah hari ini hari libur nasional
        $liburNasional = HariLibur::where('tanggal', $today)->first();
        if ($liburNasional) {
            $isLibur = true;
            $keteranganLibur = trim($liburNasional->keterangan ?? '') !== ''
            ? $liburNasional->keterangan
            : 'Hari ini adalah hari libur nasional.';
        }

        // Jika bukan hari libur, ambil jadwal dan status absensi
        if (!$isLibur) {
            $masuk = JadwalAbsen::where('tipe', 'Masuk')
                        ->where('hari', $hariIni)
                        ->orderBy('jam_mulai')
                        ->first();

            $pulang = JadwalAbsen::where('tipe', 'Pulang')
                        ->where('hari', $hariIni)
                        ->orderBy('jam_mulai')
                        ->first();

            $absen = Absen::where('user_id', $userId)
                        ->whereDate('created_at', $today)
                        ->first();

            $jadwalAda = JadwalKerja::where('user_id', $userId)
                        ->where('tanggal', $today)
                        ->exists();

            $status = 'masuk'; // default

            if ($absen) {
                if ($absen->jam_masuk && !$absen->kegiatan) {
                    $status = 'kegiatan';
                } elseif ($absen->kegiatan && !$absen->jam_pulang) {
                    $status = 'pulang';
                } elseif ($absen->jam_pulang) {
                    $status = 'selesai';
                }
            }

            if($jadwalAda) {
                $jadwal_kerja = true;
            } 
        }

        return response()->json([
            'is_libur' => $isLibur,
            'status_absen' => $isLibur ? 'libur' : $status,
            'boleh_absen' => $jadwal_kerja,
            'keterangan_libur' => $keteranganLibur,
            'jam_server' => $now->format('H:i:s'),
            'waktu_masuk_awal' => $masuk?->jam_mulai ? Carbon::parse($masuk->jam_mulai)->format('H:i') : null,
            'waktu_masuk_akhir' => $masuk?->jam_selesai ? Carbon::parse($masuk->jam_selesai)->format('H:i') : null,
            'waktu_pulang_awal' => $pulang?->jam_mulai ? Carbon::parse($pulang->jam_mulai)->format('H:i') : null,
            'waktu_pulang_akhir' => $pulang?->jam_selesai ? Carbon::parse($pulang->jam_selesai)->format('H:i') : null,
        ]);
        
    }

}
