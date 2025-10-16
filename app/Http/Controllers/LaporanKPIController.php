<?php

namespace App\Http\Controllers;

use App\Models\KegiatanUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class LaporanKPIController extends Controller
{
    public function index(Request $request)
    {
        $users = User::orderBy('name')->get();

        $bulan = $request->input('month', now()->format('Y-m'));
        [$year, $month] = explode('-', $bulan);

        $kegiatan = KegiatanUser::with('user')
            ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
            ->whereYear('tanggal_penugasan', $year)
            ->whereMonth('tanggal_penugasan', $month)
            ->get();

        $grouped = $kegiatan->groupBy('user_id')->map(function ($items, $userId) {
            $userName = $items->first()->user->name ?? 'Tanpa Nama';
            $totalPoin = $items->sum('poin');
            $totalKegiatan = $items->count();
            $selesai = $items->where('status_kegiatan', 'Selesai')->count();

            return [
                'user_id' => $userId,
                'nama' => $userName,
                'total_kegiatan' => $totalKegiatan,
                'total_poin' => $totalPoin,
                'rata_poin' => $totalKegiatan > 0 ? round($totalPoin / $totalKegiatan, 2) : 0,
                'selesai' => $selesai,
                'data' => $items->map(fn($k) => [
                    'tanggal_selesai' => $k->tanggal ?? $k->tanggal_penugasan,
                    'nama_kegiatan' => $k->nama_kegiatan,
                    'poin' => $k->poin,
                    'status' => $k->status_kegiatan,
                ]),
            ];
        })->values();

        // Pagination manual
        $perPage = 5;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $offset = ($currentPage - 1) * $perPage;

        $pagedData = new LengthAwarePaginator(
            $grouped->slice($offset, $perPage)->values(),
            $grouped->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // 🔹 Jika request AJAX → hanya kirimkan HTML parsial
        if ($request->ajax()) {
            return response()->json([
                'html' => view('laporan.kpi._partial', ['laporan' => $pagedData])->render(),
            ]);
        }

        return view('laporan.kpi.index', [
            'laporan' => $pagedData,
            'users' => $users,
            'bulan' => $bulan,
        ]);
    }
}
