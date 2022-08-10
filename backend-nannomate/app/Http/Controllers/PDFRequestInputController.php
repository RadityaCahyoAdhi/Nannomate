<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\spesies_nanofosil;
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
                'kelimpahan' => 'required|in:Kosong,Jarang,Beberapa,Umum,Melimpah',
                'preparasi' => 'required|in:Ayakan,Asahan,Smear,Lain',
                'pengawetan' => 'required|in:Jelek,Sedang,Bagus',
                'tujuan' => 'required|in:Penelitian,Tugas Akhir,Umum',
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

            //memastikan masukan spesies tambahan beserta jumlah spesies, umur awal dan akhirnya telah sesuai
            if($request->spesies_tambahan != null) {
                $spesies_nanofosil = explode(', ', $request->spesies_tambahan);
                //memastikan jumlah spesies telah dimasukkan untuk spesies tambahan
                if($request->spesies_tambahan_jumlah != null) {
                    $spesies_tambahan_jumlah = explode(', ', $request->spesies_tambahan_jumlah);
                    if(count($spesies_nanofosil) != count($spesies_tambahan_jumlah)) {
                        return response()->json(['error'=> 'Ada jumlah spesies yang belum dimasukkan'], 400);
                    }
                }
                else {
                    return response()->json(['error'=> 'Ada jumlah spesies yang belum dimasukkan'], 400);
                }

                if ($request->umur_awal_tambahan != null) {
                    $umur_awal_tambahan = explode(', ', $request->umur_awal_tambahan);
                    //memastikan elemen-elemen umur_awal_tambahan harus berupa integer
                    try {
                        $error = array('umur_awal_tambahan' => ['Elemen-elemen umur_awal_tambahan harus berupa integer']);
                        foreach($umur_awal_tambahan as $umur_awal_tambahan_value) {
                            if(!is_integer($umur_awal_tambahan_value + 1)) {
                                return response()->json(['error'=> $error], 400);
                            }
                        }
                    }
                    catch(\Exception $exception){
                        return response()->json(['error'=> $error], 400);
                    }

                    //memastikan setiap elemen umur_awal_tambahan bernilai antara 1 dan 46
                    $error = array('umur_awal_tambahan' => ['Elemen-elemen umur_awal_tambahan harus antara 1 dan 46']);
                    foreach($umur_awal_tambahan as $umur_awal_tambahan_value) {
                        if($umur_awal_tambahan_value < 1 || $umur_awal_tambahan_value > 46) {
                            return response()->json(['error'=> $error], 400);
                        }
                    }
                }

                if ($request->umur_akhir_tambahan != null) {
                    $umur_akhir_tambahan = explode(', ', $request->umur_akhir_tambahan);
                    //memastikan elemen-elemen umur_akhir_tambahan harus berupa integer
                    try {
                        $error = array('umur_akhir_tambahan' => ['Elemen-elemen umur_akhir_tambahan harus berupa integer']);
                        foreach($umur_akhir_tambahan as $umur_akhir_tambahan_value) {
                            if(!is_integer($umur_akhir_tambahan_value + 1)) {
                                return response()->json(['error'=> $error], 400);
                            }
                        }
                    }
                    catch(\Exception $exception){
                        return response()->json(['error'=> $error], 400);
                    }

                    //memastikan setiap elemen umur_akhir_tambahan bernilai antara 1 dan 46
                    $error = array('umur_akhir_tambahan' => ['Elemen-elemen umur_akhir_tambahan harus antara 1 dan 46']);
                    foreach($umur_akhir_tambahan as $umur_akhir_tambahan_value) {
                        if($umur_akhir_tambahan_value < 1 || $umur_akhir_tambahan_value > 46) {
                            return response()->json(['error'=> $error], 400);
                        }
                    }
                }

                //memastikan umur awal spesies tambahan telah dimasukkan
                if($request->umur_awal_tambahan != null) {
                    if(count($spesies_nanofosil) != count($umur_awal_tambahan)) {
                        return response()->json(['error'=> 'Ada umur awal spesies tambahan yang belum dimasukkan'], 400);
                    }
                }
                else {
                    return response()->json(['error'=> 'Ada umur awal spesies tambahan yang belum dimasukkan'], 400);
                }

                //memastikan umur akhir spesies tambahan telah dimasukkan
                if($request->umur_akhir_tambahan != null) {
                    if(count($spesies_nanofosil) != count($umur_akhir_tambahan)) {
                        return response()->json(['error'=> 'Ada umur akhir spesies tambahan yang belum dimasukkan'], 400);
                    }
                }
                else {
                    return response()->json(['error'=> 'Ada umur akhir spesies tambahan yang belum dimasukkan'], 400);
                }

                //memastikan setiap elemen umur_akhir_tambahan lebih dari atau sama dengan setiap elemen umur_awal_tambahan nya
                if($request->umur_awal_tambahan != null && $request->umur_akhir_tambahan != null) {
                    for ($i=0; $i < count($umur_awal_tambahan); $i++) {
                        if ($umur_awal_tambahan[$i] > $umur_akhir_tambahan[$i]) {
                            return response()->json(['error'=> 'Ada umur awal melebihi umur akhir spesies tambahan'], 400);
                        }
                    }
                }
            }

            //mengumpulkan data zona geologi spesies tambahan
            $zona_geologi = [];
            if($request->spesies_tambahan != null) {
                if($request->umur_awal_tambahan != null && $request->umur_akhir_tambahan != null) {
                    for ($i=0; $i < count($umur_awal_tambahan); $i++) {
                        $zona_geologi_tambahan = [];
                        for($j=0; $j <= ($umur_akhir_tambahan[$i] - $umur_awal_tambahan[$i]); $j++) {
                            $zona_geologi_tambahan[$j]['id_umur'] = ($umur_awal_tambahan[$i] + $j);
                        }
                        $zona_geologi[$i] = $zona_geologi_tambahan;
                    }
                }
            }

            //memastikan elemen-elemen id_spesies terdaftar dalam database
            if ($request->id_spesies != null) {
                foreach ($id_spesies as $id_spesies_value) {
                    if (is_null(spesies_nanofosil::find($id_spesies_value))){
                        $error = array('id_spesies' => ['Data Not Found!']);
                        return response()->json(['error'=> $error], 404);
                    }
                }
            }

            return Excel::download(new SampleExportByRequest($request->nama_observer, $request->tanggal_penelitian, $request->lokasi, $request->litologi, $request->formasi, $request->longitude, $request->latitude, $request->kode_sample, $request->kelimpahan, $request->preparasi, $request->pengawetan, $request->tujuan, $request->stopsite, $request->id_spesies, $request->spesies_tambahan, $request->id_spesies_jumlah, $request->spesies_tambahan_jumlah, $zona_geologi), 'Fossil List Export '.now()->format('Y-m-d H.i.s').'.pdf');
        }
    }
}
