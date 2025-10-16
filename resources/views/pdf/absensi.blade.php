<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Absensi</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #000;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th, td {
            border: 1px solid #444;
            padding: 6px 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .alamat {
            font-size: 11px;
            color: #555;
        }
    </style>
</head>
<body>
    <h2>Riwayat Absensi</h2>

    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th>Jumlah Jam</th> 
                <th>Kegiatan</th>
                <th>Lokasi Masuk</th>
                <th>Lokasi Pulang</th>
            </tr>
        </thead>
        <tbody>
        @foreach($absens as $absen)
        @php
            $jamKerja = '-';
            if ($absen->jam_masuk && $absen->jam_pulang) {
                $masuk = \Carbon\Carbon::parse($absen->jam_masuk);
                $pulang = \Carbon\Carbon::parse($absen->jam_pulang);
                $selisih = $masuk->diff($pulang);
                $jamKerja = sprintf('%02d:%02d', $selisih->h, $selisih->i);
            }
        @endphp
        <tr>
            <td>{{ $absen->user->name }}</td>
            <td>{{ $absen->tanggal }}</td>
            <td>{{ $absen->jam_masuk ?? '-' }}</td>
            <td>{{ $absen->jam_pulang ?? '-' }}</td>
            <td>{{ $jamKerja }}</td> <!-- Tambahkan hasil perhitungan -->
            <td>{!! nl2br(e($absen->kegiatan)) !!}</td>
            <td>
                {{ $absen->lat_masuk }}, {{ $absen->lon_masuk }}<br>
                @if($absen->alamat_masuk)
                    <span class="alamat">{{ $absen->alamat_masuk }}</span>
                @endif
            </td>
            <td>
                {{ $absen->lat_pulang }}, {{ $absen->lon_pulang }}<br>
                @if($absen->alamat_pulang)
                    <span class="alamat">{{ $absen->alamat_pulang }}</span>
                @endif
            </td>
        </tr>
    @endforeach

        </tbody>
    </table>

    <div style="text-align: right; font-size: 11px; margin-top: 20px;">
        Dicetak pada: {{ now()->format('d-m-Y H:i') }}
    </div>
</body>
</html>
