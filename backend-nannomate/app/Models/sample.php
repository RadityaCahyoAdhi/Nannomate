<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sample extends Model
{
    use HasFactory;

    //define table name
    protected $table = 'sample';
    //define primary key
    protected $primaryKey = 'id_sample';
    //disable timestamps
    public $timestamps = false;
    //fillable column
    protected $fillable = [
        'id_studi_area', 'id_spesies', 'id_user', 'kode_sample', 'kelimpahan', 'preparasi', 'pengawetan', 'tujuan', 'stopsite', 'status', 'alasan', 'tanggal_dikirim', 'tanggal_diterima'
    ];
}
