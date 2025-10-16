<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absen;
class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getAbsensiData(Request $request)
{
    $query = Absen::with(['user', 'jadwalAbsen'])
        ->when($request->tanggal_mulai, fn($q) => $q->where('tanggal', '>=', $request->tanggal_mulai))
        ->when($request->tanggal_sampai, fn($q) => $q->where('tanggal', '<=', $request->tanggal_sampai))
        ->when($request->nama, fn($q) => $q->whereHas('user', fn($q2) => $q2->where('name', 'like', '%' . $request->nama . '%')))
        ->orderBy('tanggal', 'desc')
        ->get()
        ->groupBy(fn($absen) => $absen->user->name);

    $data = [];

    foreach ($query as $userName => $absens) {
        $data[] = [
            'name' => $userName,
            'absens' => $absens->map(fn($a) => [
                'tanggal' => $a->tanggal,
                'jam_masuk' => $a->jam_masuk,
                'jam_pulang' => $a->jam_pulang,
                'lat_masuk' => $a->lat_masuk,
                'lon_masuk' => $a->lon_masuk,
                'lat_pulang' => $a->lat_pulang,
                'lon_pulang' => $a->lon_pulang,
                'alamat_masuk' => $a->alamat_masuk,
                'alamat_pulang' => $a->alamat_pulang,
                'kegiatan' => $a->kegiatan
            ])
        ];
    }

    return response()->json($data);
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
