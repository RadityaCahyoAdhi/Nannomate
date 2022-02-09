<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class umur_geologi extends Model
{
    use HasFactory;

    //define table name
    protected $table = 'umur_geologi';
    //define primary key
    protected $primaryKey = 'id_umur';
    //disable timestamps
    public $timestamps = false;

    public function zonaGeologi()
    {
        return $this->hasMany(zona_geologi::class, 'id_umur', 'id_umur');
    }
}
