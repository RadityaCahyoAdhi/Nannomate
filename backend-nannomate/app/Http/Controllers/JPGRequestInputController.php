<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Exports\SampleExportByRequest;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\PdfToImage\Pdf;
use Org_Heigl\Ghostscript\Ghostscript;

class JPGRequestInputController extends Controller
{
    public function export(Request $request)
    {
        $user = Auth::user();
        if ($user['role'] != 'user login') {
            return response()->json(['error'=>'Unauthorised'], 401);
        } else {
            $validator = Validator::make($request->all(), [
                //observer table
                'nama_observer' => 'required',
                'tanggal_penelitian' => 'required',
                //studi_area table
                'lokasi' => 'required',
                'litologi' => 'required',
                'formasi' => 'required',
                'longitude' => 'required',
                'latitude' => 'required',
                //sample table
                'kode_sample' => 'required',
                'kelimpahan' => 'required',
                'preparasi' => 'required',
                'pengawetan' => 'required',
                'tujuan' => 'required',
                'stopsite' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401);
            }

            //memastikan jumlah spesies telah dimasukkan untuk spesies yang telah terdaftar dalam database
            if($request->id_spesies != null) {
                $id_spesies = explode(', ', $request->id_spesies);
                if($request->id_spesies_jumlah != null) {
                    $id_spesies_jumlah = explode(', ', $request->id_spesies_jumlah);
                    if(count($id_spesies) != count($id_spesies_jumlah)) {
                        return response()->json(['error'=> 'Ada jumlah spesies yang belum dimasukkan'], 401);
                    }
                }
                else {
                    return response()->json(['error'=> 'Ada jumlah spesies yang belum dimasukkan'], 401);
                }
            }

            //memastikan jumlah spesies telah dimasukkan untuk spesies tambahan
            if($request->spesies_tambahan != null) {
                $spesies_nanofosil = explode(', ', $request->spesies_tambahan);
                if($request->spesies_tambahan_jumlah != null) {
                    $spesies_tambahan_jumlah = explode(', ', $request->spesies_tambahan_jumlah);
                    if(count($spesies_nanofosil) != count($spesies_tambahan_jumlah)) {
                        return response()->json(['error'=> 'Ada jumlah spesies yang belum dimasukkan'], 401);
                    }
                }
                else {
                    return response()->json(['error'=> 'Ada jumlah spesies yang belum dimasukkan'], 401);
                }
            }

            Ghostscript::setGsPath('C:\Program Files\gs\gs9.54.0\bin\gswin64c.exe');
            $file_name = 'Fossil List Export '.now()->format('Y-m-d H.i.s');
            Excel::store(new SampleExportByRequest($request->nama_observer, $request->tanggal_penelitian, $request->lokasi, $request->litologi, $request->formasi, $request->longitude, $request->latitude, $request->kode_sample, $request->kelimpahan, $request->preparasi, $request->pengawetan, $request->tujuan, $request->stopsite, $request->id_spesies, $request->spesies_tambahan, $request->id_spesies_jumlah, $request->spesies_tambahan_jumlah), $file_name.'.pdf', 'public');

            $pdf = new Pdf(public_path('storage/'.$file_name.'.pdf'));
            $pdf->saveImage(public_path('storage/'.$file_name.'.jpg'));
            Storage::disk('public')->delete($file_name.'.pdf');
            return response()->download(public_path('storage/'.$file_name.'.jpg'))->deleteFileAfterSend(true);
        }
    }
}
