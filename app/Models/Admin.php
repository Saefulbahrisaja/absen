<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;

class Admin extends Model implements AuthenticatableContract
{
    use HasFactory, Notifiable, Authenticatable;

    protected $table = 'admin'; 
    protected $guard = 'admin';

    protected $fillable = ['nama_admin', 'email', 'password_admin'];

    protected $hidden = ['password_admin', 'remember_token'];

    public function getAuthPassword()
    {
        return $this->password_admin;
    }
}
