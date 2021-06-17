<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class studi_area extends Model
{
    use HasFactory;

    //define table name
    protected $table = 'studi_area';
    //define primary key
    protected $primaryKey = 'id_studi_area';
    //disable timestamps
    public $timestamps = false;
    //fillable column
    protected $fillable = [
        'id_observer', 'lokasi', 'litologi', 'formasi', 'longitude', 'latitude'
    ];

    public function observer()
    {
        return $this->belongsTo(observer::class);
    }
}
