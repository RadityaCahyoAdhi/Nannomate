<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\sample;
use App\Exports\SampleExport;
use Maatwebsite\Excel\Facades\Excel;

class ExcelDBInputController extends Controller
{
    public function export($id)
    {
        $sample = sample::find($id);
        if (is_null($sample)){
            return response()->json(['error'=>'Data Not Found!'], 404);
        }
        return Excel::download(new SampleExport($id), 'Fossil List Export '.now()->format('Y-m-d H.i.s').'.xlsx');
    }
}
