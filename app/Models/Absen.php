<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\JadwalAbsen;
class Absen extends Model
{
    protected $table = 'absen'; 

    protected $fillable = [
        'user_id',
        'jam_masuk',
        'jam_pulang',
        'lat_masuk',
        'lon_masuk',
        'lat_pulang',
        'lon_pulang',
        'tanggal',
        'kegiatan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function kegiatan()
    {
        return $this->hasMany(KegiatanUser::class, 'user_id', 'user_id');
    }
   
    public function jadwalAbsen()
    {
        return $this->belongsTo(JadwalAbsen::class, 'jadwal_absen_id'); // sesuaikan kolom foreign key-nya
    }

        public function jadwalKerja()
    {
        return $this->belongsTo(JadwalKerja::class, 'user_id', 'user_id');
    }

    
    protected $dates = ['jam_masuk', 'jam_pulang', 'created_at', 'updated_at'];
}
