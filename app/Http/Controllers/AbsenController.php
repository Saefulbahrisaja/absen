<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absen;
use App\Models\HariLibur;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;


class AbsenController extends Controller
{
    

public function index()
{
    $absens = Absen::with('user')->orderBy('tanggal', 'desc')->get();

    // Ambil hari libur
    $libur = HariLibur::pluck('tanggal')->toArray();
    foreach ($absens as $absen) {
        if ($absen->lat_masuk && $absen->lon_masuk) {
            $absen->alamat_masuk = $this->getAddress($absen->lat_masuk, $absen->lon_masuk);
        }
    
        if ($absen->lat_pulang && $absen->lon_pulang) {
            $absen->alamat_pulang = $this->getAddress($absen->lat_pulang, $absen->lon_pulang);
        }
    }
    $absens = Absen::with('user', 'jadwalAbsen')->get();

    return view('dashboard.absensi', compact('absens', 'libur'));
}


private function getAddress($lat, $lon)
{
    $apiKey = env('LOCATIONIQ_API_KEY');
    $response = Http::get("https://us1.locationiq.com/v1/reverse", [
        'key' => $apiKey,
        'lat' => $lat,
        'lon' => $lon,
        'format' => 'json'
    ]);

    if ($response->successful()) {
        return $response->json()['display_name'] ?? 'Alamat tidak ditemukan';
    }

    return 'Gagal mendapatkan alamat';
}

public function exportPdf(Request $request)
{
    $query = Absen::with('user');

    if ($request->filled('tanggal_mulai')) {
        $query->where('tanggal', '>=', $request->tanggal_mulai);
    }
    if ($request->filled('tanggal_sampai')) {
        $query->where('tanggal', '<=', $request->tanggal_sampai);
    }

    $absens = $query->get();

    $pdf = PDF::loadView('pdf.absensi', compact('absens'));
    return $pdf->download('riwayat-absensi.pdf');
}



}
