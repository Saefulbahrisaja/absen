<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class JadwalAbsen extends Model
{
    protected $table = 'jadwal_absen'; 

    protected $fillable = [
        'tipe',
        'hari',
        'jam_mulai',
        'jam_selesai',
    ];
}