<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absen;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\KegiatanUser;
use App\Models\JadwalKerja;

class AbsenController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'jam_masuk' => 'nullable|date_format:H:i',
            'jam_pulang' => 'nullable|date_format:H:i',
            'lat_masuk' => 'nullable|numeric',
            'lon_masuk' => 'nullable|numeric',
            'lat_pulang' => 'nullable|numeric',
            'lon_pulang' => 'nullable|numeric',
            'tanggal' => 'nullable|date_format:Y-m-d',
            'kegiatan' => 'nullable|string',
            'status' => 'required|in:masuk,pulang',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Data tidak valid', 'errors' => $validator->errors()], 422);
        }

        $user_id = $request->user_id;
        $status = $request->status;
        $tanggal = Carbon::now()->toDateString();

        $absen = Absen::where('user_id', $user_id)
            ->whereDate('tanggal', $tanggal)
            ->first();

        if ($status === 'masuk') {
            if ($absen && $absen->jam_masuk) {
                return response()->json(['message' => 'Sudah absen masuk hari ini'], 400);
            }

            if (!$absen) {
                $absen = new Absen();
                $absen->user_id = $user_id;
                $absen->kegiatan = '';
                $absen->jam_masuk = null;
                $absen->jam_pulang = null;
                $absen->lat_masuk = null;     
                $absen->tanggal = $tanggal;
            }

            $absen->jam_masuk = Carbon::now();
            $absen->lat_masuk = $request->latitude;
            $absen->lon_masuk = $request->longitude;
            $absen->save();

            return response()->json(['message' => 'Absen masuk berhasil'], 200);
        }

        if ($status === 'pulang') {
            if (!$absen || !$absen->jam_masuk) {
                return response()->json(['message' => 'Belum absen masuk'], 400);
            }

            if ($absen->jam_pulang) {
                return response()->json(['message' => 'Sudah absen pulang hari ini'], 400);
            }

            $absen->jam_pulang = Carbon::now();
            $absen->lat_pulang = $request->latitude;
            $absen->lon_pulang = $request->longitude;
            $absen->save();

            return response()->json(['message' => 'Absen pulang berhasil'], 200);
        }
    }

    public function storeKegiatan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'kegiatan' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Data tidak valid', 'errors' => $validator->errors()], 422);
        }

        $user_id = $request->user_id;
        $tanggal = Carbon::now()->toDateString();

        $absen = Absen::where('user_id', $user_id)
            ->whereDate('tanggal', $tanggal)
            ->first();

        if (!$absen || !$absen->jam_masuk) {
            return response()->json(['message' => 'Belum absen masuk'], 400);
        }

        $absen->kegiatan = $request->kegiatan;

        // Otomatis isi jam_pulang jika belum
        //if (!$absen->jam_pulang) {
            //$absen->jam_pulang = Carbon::now();
        //}

        $absen->save();

        return response()->json(['message' => 'Kegiatan berhasil disimpan dan status absen diperbarui'], 200);
    }

    public function getToday(Request $request, $user_id)
    {
        $tanggal = Carbon::now()->toDateString();

        $absen = Absen::where('user_id', $user_id)
            ->whereDate('tanggal', $tanggal)
            ->first();

        if (!$absen) {
            return response()->json(['message' => 'Belum ada absen hari ini'], 404);
        }

        return response()->json([
            'jam_masuk' => $absen?->jam_masuk ? Carbon::parse($absen->jam_masuk)->format('H:i') : null,
            'jam_pulang' => $absen?->jam_pulang ? Carbon::parse($absen->jam_pulang)->format('H:i') : null,
            'lat_masuk' => $absen->lat_masuk,
            'lon_masuk' => $absen->lon_masuk,
            'lat_pulang' => $absen->lat_pulang,
            'lon_pulang' => $absen->lon_pulang,
            'kegiatan' => $absen->kegiatan,
        ]);
    }

    public function getAll($user_id)
    {
        $absenList = Absen::where('user_id', $user_id)
            ->orderBy('tanggal', 'desc')
            ->get()
            ->map(function ($absen) {
                $absen->keterangan_masuk = $this->getKeteranganMasuk($absen->tanggal, $absen->jam_masuk);
                return $absen;
            });

        return response()->json($absenList);
    }


    public function getAllKegiatan($user_id)
    {
        $kegiatanList = KegiatanUser::where('user_id', $user_id)
            //->whereMonth('created_at', $now->month)
            //->whereYear('created_at', $now->year)
            ->orderBy('status_kegiatan', 'asc')
            ->orderBy('nama_kegiatan', 'asc')
            ->get();

        return response()->json($kegiatanList);
    }

    public function getByMonth(Request $request, $user_id)
    {
        $month = $request->query('month');
        $year = $request->query('year');

        if (!$month || !$year) {
            return response()->json(['message' => 'Bulan dan tahun wajib diisi'], 400);
        }

        $absenList = Absen::where('user_id', $user_id)
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->orderBy('tanggal', 'desc')
            ->get()
            ->map(function ($absen) {
                $absen->keterangan_masuk = $this->getKeteranganMasuk($absen->tanggal, $absen->jam_masuk);
                return $absen;
            });

        return response()->json($absenList);
    }
    private function getKeteranganMasuk($tanggal, $jamMasuk)
    {
        $hari = strtolower(Carbon::parse($tanggal)->locale('id')->isoFormat('dddd'));

        $jadwalMasuk = \App\Models\JadwalAbsen::where('tipe', 'Masuk')
            ->where('hari', $hari)
            ->first();

        if (!$jadwalMasuk || !$jamMasuk) return null;

        return $jamMasuk <= $jadwalMasuk->jam_selesai ? 'Tepat Waktu' : 'Terlambat';
    }

    public function listKegiatanByUser(Request $request)
    {
        $userId = auth()->id(); 
        $kegiatan = KegiatanUser::where('user_id', $userId)
                    ->where('status_kegiatan', '!=', 'Selesai') // Filter status
                    ->pluck('nama_kegiatan');

        return response()->json($kegiatan);
    }

    public function getJadwalKerja(Request $request)
    {
        $userId = auth()->id();

        $kegiatan = JadwalKerja::where('user_id', $userId)
                    ->pluck('tanggal');

        return response()->json([
            'success' => true,
            'data' => $kegiatan
        ]);
    }

    public function updateStatusKegiatan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'nama_kegiatan' => 'required|string', 
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Data tidak valid',
                'errors' => $validator->errors()
            ], 422);
        }

        $userId = $request->user_id;
        // Pisahkan string menjadi array kegiatan
        $kegiatanList = explode(',', $request->nama_kegiatan);

        // Update status 'selesai' hanya untuk kegiatan yang diceklis
        KegiatanUser::where('user_id', $userId)
            ->whereIn('nama_kegiatan', $kegiatanList)
            ->update(['status_kegiatan' => 'Selesai',
                      'tanggal_selesai' => Carbon::now()->toDateString(),]);

        return response()->json([
            'message' => 'Status kegiatan berhasil diperbarui'
        ], 200);
    }

}