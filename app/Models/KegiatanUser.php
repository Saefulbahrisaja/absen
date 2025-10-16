<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class KegiatanUser extends Model
{
    protected $table = 'kegiatan_user'; 
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_kegiatan',
        'tanggal_penugasan',
        'tanggal_selesai',
        'poin',  
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    
}
