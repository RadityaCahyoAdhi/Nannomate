<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\observer;
use App\Models\studi_area;
use App\Models\sample;


class DaftarDataPenelitianController extends Controller
{
    public function index()
    {
        $daftarDataPenelitian = observer::join('studi_area','studi_area.id_observer', '=', 'observer.id_observer')
        ->join('sample', 'sample.id_studi_area', '=', 'studi_area.id_studi_area')
        ->get(['sample.id_sample', 'observer.nama_observer', 'studi_area.lokasi', 'studi_area.formasi', 'sample.kode_sample', 'sample.tujuan']);

        return response()->json($daftarDataPenelitian, 200);
    }
}
