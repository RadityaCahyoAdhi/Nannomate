<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class observer extends Model
{
    use HasFactory;

    //define table name
    protected $table = 'observer';
    //define primary key
    protected $primaryKey = 'id_observer';
    //disable timestamps
    public $timestamps = false;
    //fillable column
    protected $fillable = [
        'nama_observer', 'tanggal_penelitian'
    ];

    public function studiArea()
    {
        return $this->hasOne(studi_area::class, 'id_observer', 'id_observer');
    }
}
