<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\observer;
use App\Models\studi_area;
use App\Models\sample;
use App\Models\sample_spesies;
use App\Models\spesies_nanofosil;
use App\Models\zona_geologi;
use App\Models\umur_geologi;
use App\Exports\SampleExport;
use Maatwebsite\Excel\Facades\Excel;

class JPGDBInputController extends Controller
{
    public function storeExcel($id)
    {
        // Store on default disk
        // Excel::store(new SampleExport($id), 'Fossil List Export'.now().'.pdf', 'public');
        $filename = 'Fossil List Export'.time().'.pdf';
        Excel::store(new SampleExport($id), $filename);
    }
}
