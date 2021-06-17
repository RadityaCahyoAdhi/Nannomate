<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class spesies_nanofosil extends Model
{
    use HasFactory;

    //define table name
    protected $table = 'spesies_nanofosil';
    //define primary key
    protected $primaryKey = 'id_spesies';
    //disable timestamps
    public $timestamps = false;
    //fillable column
    protected $fillable = [
        'nama_spesies', 'status'
    ];
}
