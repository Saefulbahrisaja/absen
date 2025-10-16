<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HariLibur extends Model
{
    protected $table = 'hari_libur'; 

    protected $fillable = [
        'tanggal',
        'keterangan',
    ];
      
    protected $dates = ['created_at', 'updated_at'];
}
