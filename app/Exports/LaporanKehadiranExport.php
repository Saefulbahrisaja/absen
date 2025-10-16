<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class LaporanKehadiranExport implements FromArray, WithHeadings
{
    protected $laporan;

    public function __construct($laporan)
    {
        $this->laporan = $laporan;
    }

    public function array(): array
    {
        $data = [];
        
        foreach ($this->laporan as $user) {
            // Master - User summary
            $data[] = [
                'Nama' => $user['nama'],
                'Bulan' => Carbon::createFromFormat('Y-m', $user['bulan'])->translatedFormat('F Y'),
                'Total Telat' => $user['total_telat'],
                'Total Tepat Waktu' => $user['total_tepat'],
                'Total Kehadiran' => count($user['data']),
            ];

            // Detail - Attendance records
            foreach ($user['data'] as $row) {
                $data[] = [
                    'Tanggal' => Carbon::parse($row['tanggal'])->translatedFormat('d M Y'),
                    'Jam Masuk' => $row['jam_masuk'],
                    'Jam Keluar' => $row['jam_keluar'],
                    'Lokasi Masuk' => $row['lokasi_masuk'],
                    'Lokasi Keluar' => $row['lokasi_keluar'],
                    'Status Masuk' => $row['status_masuk'],
                    'Status Keluar' => $row['status_keluar'],
                    'Telat' => $row['telat'],
                    'Kegiatan' => $row['kegiatan'],
                ];
            }

            // Insert an empty row after each user's detail records for better clarity
            $data[] = [];  // Adds an empty row after user detail records
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Nama', 'Bulan', 'Total Telat', 'Total Tepat Waktu', 'Total Kehadiran',
            'Tanggal', 'Jam Masuk', 'Jam Keluar', 'Lokasi Masuk', 'Lokasi Keluar',
            'Status Masuk', 'Status Keluar', 'Telat', 'Kegiatan',
        ];
    }

    public function styles(Sheet $sheet)
    {
        // Bold header row
        return [
            1    => ['font' => ['bold' => true]],  // Header row (1st row) bolded
            'A1:N1' => ['font' => ['bold' => true]],  // Set bold on the first row (headings)
            'A:N'  => ['alignment' => ['horizontal' => 'center']],  // Center align all columns
            'A1:N1' => ['fill' => ['fillType' => 'solid', 'startColor' => 'FFFF00']], // Yellow background on headers
        ];
    }
}
