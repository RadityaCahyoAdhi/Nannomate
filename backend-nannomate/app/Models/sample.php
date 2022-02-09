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
        'id_studi_area', 'id_user', 'kode_sample', 'kelimpahan', 'preparasi', 'pengawetan', 'tujuan', 'stopsite', 'status', 'alasan', 'tanggal_dikirim', 'tanggal_diterima'
    ];

    public function studiArea()
    {
        return $this->belongsTo(studi_area::class, 'id_studi_area', 'id_studi_area');
    }

    public function user()
    {
        return $this->belongsTo(user::class, 'id_user', 'id_user');
    }

    public function sampleSpesies()
    {
        return $this->hasMany(sample_spesies::class, 'id_sample', 'id_sample');
    }
}
