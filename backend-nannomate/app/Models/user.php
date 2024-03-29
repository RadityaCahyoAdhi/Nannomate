<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class user extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    //define table name
    protected $table = 'user';
    //define primary key
    protected $primaryKey = 'id_user';
    //disable timestamps
    public $timestamps = false;
    //fillable column
    protected $fillable = [
        'nama_lengkap', 'email', 'password', 'role', 'status'
    ];

    public function sample()
    {
        return $this->hasMany(sample::class, 'id_user', 'id_user');
    }
}
