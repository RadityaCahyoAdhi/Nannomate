<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sample_spesies extends Model
{
    use HasFactory;

    //define table name
    protected $table = 'sample_spesies';
    //define primary key
    protected $primaryKey = 'id_sample_spesies';
    //disable timestamps
    public $timestamps = false;
    //fillable column
    protected $fillable = [
        'id_sample', 'id_spesies', 'jumlah'
    ];
}
