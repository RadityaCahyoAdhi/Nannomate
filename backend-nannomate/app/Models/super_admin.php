<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class super_admin extends Model
{
    use HasFactory;

    //define table name
    protected $table = 'super_admin';
    //define primary key
    protected $primaryKey = 'id_super_admin';
    //disable timestamps
    public $timestamps = false;
}
