<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalKerja extends Model
{
    use HasFactory;
    protected $table = 'jadwal_kerja';
    protected $fillable = ['user_id', 'tanggal'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
