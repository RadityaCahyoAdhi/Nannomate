<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\observer;
use App\Models\sample_spesies;
use App\Models\spesies_nanofosil;

class DaftarDataPenelitianController extends Controller
{
    public function index()
    {
        $daftarDataPenelitian = observer::join('studi_area','studi_area.id_observer', '=', 'observer.id_observer')
        ->join('sample', 'sample.id_studi_area', '=', 'studi_area.id_studi_area')
        ->where('status', '=', 'diterima')
        ->get(['sample.id_sample', 'observer.nama_observer', 'studi_area.lokasi', 'studi_area.formasi', 'sample.kode_sample', 'sample.tujuan']);

        // get each sample's sample_spesies
        $i = 0;
        foreach ($daftarDataPenelitian as $dataPenelitian) {
            $dataSampleSpesies[$i] = sample_spesies::where('id_sample', '=', $dataPenelitian->id_sample)->get();
            
            // get each sample's spesies_nanofosil
            $j = 0;
            $dataSpesies = [];
            foreach ($dataSampleSpesies[$i] as $dataSampleSpesiesValue) {
                $dataSpesies[$j] = spesies_nanofosil::where('id_spesies', '=', $dataSampleSpesiesValue->id_spesies)->get()[0]->nama_spesies;
                $j++;
            }
            
            $daftarDataPenelitian[$i]->spesies = $dataSpesies;
            $i++;
        }

        return response()->json($daftarDataPenelitian, 200);
    }
}
