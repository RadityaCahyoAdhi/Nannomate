<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\sample;
use App\Exports\SampleExport;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\PdfToImage\Pdf;
use Org_Heigl\Ghostscript\Ghostscript;

class JPGDBInputController extends Controller
{
    public function export($id)
    {
        $sample = sample::find($id);
        if (is_null($sample)){
            return response()->json(['error'=>'Data Not Found!'], 404);
        }
        Ghostscript::setGsPath('C:\Program Files\gs\gs9.54.0\bin\gswin64c.exe');
        $file_name = 'Fossil List Export '.now()->format('Y-m-d H.i.s');
        Excel::store(new SampleExport($id), $file_name.'.pdf', 'public');

        $pdf = new Pdf(public_path('storage/'.$file_name.'.pdf'));
        $pdf->saveImage(public_path('storage/'.$file_name.'.jpg'));
        Storage::disk('public')->delete($file_name.'.pdf');
        return response()->download(public_path('storage/'.$file_name.'.jpg'))->deleteFileAfterSend(true);
    }
}
