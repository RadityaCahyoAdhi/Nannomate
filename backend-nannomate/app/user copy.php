<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user extends Model
{
    use HasFactory;

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
}
