<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class zona_geologi extends Model
{
    use HasFactory;

    //define table name
    protected $table = 'zona_geologi';
    //define primary key
    protected $primaryKey = 'id_zona';
    //disable timestamps
    public $timestamps = false;
    //fillable column
    protected $fillable = [
        'id_spesies', 'id_umur'
    ];

    public function spesiesNanofosil()
    {
        return $this->belongsTo(spesies_nanofosil::class, 'id_spesies', 'id_spesies');
    }

    public function umurGeologi()
    {
        return $this->belongsTo(umur_geologi::class, 'id_umur', 'id_umur');
    }
}
