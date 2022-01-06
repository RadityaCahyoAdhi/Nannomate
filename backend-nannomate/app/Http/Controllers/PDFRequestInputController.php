<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Exports\SampleExportByRequest;
use Maatwebsite\Excel\Facades\Excel;

class PDFRequestInputController extends Controller
{
    public function export(Request $request)
    {
        $user = Auth::user();
        if ($user['role'] != 'user login') {
            return response()->json(['error'=>'Unauthorised'], 403);
        } else {
            $validator = Validator::make($request->all(), [
                //observer table
                'nama_observer' => 'required',
                'tanggal_penelitian' => 'required|date_format:Y-m-d',
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

            //memastikan variabel-variabel yang dibutuhkan tersedia
            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 400);
            }

            //memastikan masukan spesies yang telah terdaftar dalam database beserta jumlahnya telah sesuai
            if($request->id_spesies != null) {
                $id_spesies = explode(', ', $request->id_spesies);
                //memastikan elemen-elemen id_spesies harus berupa integer
                try {
                    $error = array('id_spesies' => ['Elemen-elemen id_spesies harus berupa integer']);
                    foreach($id_spesies as $id_spesies_value) {
                        if(!is_integer($id_spesies_value + 1)) {
                           return response()->json(['error'=> $error], 400);
                        }
                    }
                }
                catch(\Exception $exception){
                    return response()->json(['error'=> $error], 400);
                }
                //memastikan jumlah spesies telah dimasukkan untuk spesies yang telah terdaftar dalam database
                if($request->id_spesies_jumlah != null) {
                    $id_spesies_jumlah = explode(', ', $request->id_spesies_jumlah);
                    if(count($id_spesies) != count($id_spesies_jumlah)) {
                        return response()->json(['error'=> 'Ada jumlah spesies yang belum dimasukkan'], 400);
                    }
                }
                else {
                    return response()->json(['error'=> 'Ada jumlah spesies yang belum dimasukkan'], 400);
                }
            }

            //memastikan jumlah spesies telah dimasukkan untuk spesies tambahan
            if($request->spesies_tambahan != null) {
                $spesies_nanofosil = explode(', ', $request->spesies_tambahan);
                if($request->spesies_tambahan_jumlah != null) {
                    $spesies_tambahan_jumlah = explode(', ', $request->spesies_tambahan_jumlah);
                    if(count($spesies_nanofosil) != count($spesies_tambahan_jumlah)) {
                        return response()->json(['error'=> 'Ada jumlah spesies yang belum dimasukkan'], 400);
                    }
                }
                else {
                    return response()->json(['error'=> 'Ada jumlah spesies yang belum dimasukkan'], 400);
                }
            }

            return Excel::download(new SampleExportByRequest($request->nama_observer, $request->tanggal_penelitian, $request->lokasi, $request->litologi, $request->formasi, $request->longitude, $request->latitude, $request->kode_sample, $request->kelimpahan, $request->preparasi, $request->pengawetan, $request->tujuan, $request->stopsite, $request->id_spesies, $request->spesies_tambahan, $request->id_spesies_jumlah, $request->spesies_tambahan_jumlah), 'Fossil List Export '.now()->format('Y-m-d H.i.s').'.pdf');
        }
    }
}
