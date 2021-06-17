<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Models\spesies_nanofosil;
use App\Models\zona_geologi;
use App\Models\umur_geologi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpesiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index() {
    //     $daftarSpesies = spesies_nanofosil::all();

    //     return response()->json($daftarSpesies, 200);
    // }

    public function index() {
        $user = Auth::user();
        if ($user['role'] != 'user login') {
            return response()->json(['error'=>'Unauthorised'], 401);
        } else {
            $daftarSpesiesTerverifikasi = spesies_nanofosil::where('status', '=', 'terverifikasi')->get();

            return response()->json($daftarSpesiesTerverifikasi, 200);
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user['role'] != 'admin') {
            return response()->json(['error'=>'Unauthorised'], 401);
        } else {
            //find spesies terverifikasi same or new
            $hit = spesies_nanofosil::where('nama_spesies', '=', $request->nama_spesies)->where('status', '=', 'terverifikasi')->get();
            $hit = $hit->count();
            if ($hit > 0) {
                return response()->json(['error'=>'Spesies already exists!'], 404);
            } else {
                $validator = Validator::make($request->all(), [
                    'nama_spesies' => 'required',
                    'id_umur_awal' => 'required',
                    'id_umur_akhir' => 'required'
                ]);

                if ($validator->fails()) {
                    return response()->json(['error'=>$validator->errors()], 401);
                }

                $input = $request->all();
                $spesies_nanofosil = spesies_nanofosil::create([
                    'nama_spesies' => $input['nama_spesies'],
                    'status' => 'terverifikasi'
                ]);

                $input_zona_geologi['id_spesies'] = $spesies_nanofosil['id_spesies'];
                $umur_awal = $input['id_umur_awal'];
                $umur_akhir= $input['id_umur_akhir'];

                for ($i = $umur_awal; $i <= $umur_akhir; $i++) {
                    $input_zona_geologi['id_umur'] = $i;
                    zona_geologi::create([
                        'id_spesies' => $input_zona_geologi['id_spesies'],
                        'id_umur' => $input_zona_geologi['id_umur']]);
                }

                return response()->json(['success'=> 'data successfully created'], 200);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $spesies_nanofosil['spesies_nanofosil'] = spesies_nanofosil::find($id);
        $spesies_nanofosil['zona_geologi'] = zona_geologi::where('id_spesies', '=', $id)->get();

        return response()->json($spesies_nanofosil, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if ($user['role'] != 'admin') {
            return response()->json(['error'=>'Unauthorised'], 401);
        } else {
            $spesies_nanofosil = spesies_nanofosil::find($id);
            if (is_null($spesies_nanofosil)){
                return response()->json(['error'=>'Data Not Found!'], 404);
            }
            $validator = Validator::make($request->all(), [
                'nama_spesies' => 'required',
                'id_umur_awal' => 'required',
                'id_umur_akhir' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401);
            }

            if ($request->id_umur_awal > $request->id_umur_akhir) {
                return response()->json(['error' => 'Umur awal melebihi umur akhir'], 401);
            }

            $input = $request->all();
            spesies_nanofosil::where('id_spesies', $id)->update([
                'nama_spesies' => $input['nama_spesies']
            ]);

            zona_geologi::where('id_spesies', '=', $id)->delete();

            $input_zona_geologi['id_spesies'] = $id;
            $umur_awal = $input['id_umur_awal'];
            $umur_akhir= $input['id_umur_akhir'];

            for ($i = $umur_awal; $i <= $umur_akhir; $i++) {
                $input_zona_geologi['id_umur'] = $i;
                zona_geologi::create([
                    'id_spesies' => $input_zona_geologi['id_spesies'],
                    'id_umur' => $input_zona_geologi['id_umur']]);
            }

            return response()->json(['success' => 'Data successfully updated'], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Auth::user();
        if ($user['role'] != 'admin') {
            return response()->json(['error'=>'Unauthorised'], 401);
        } else {
            $spesies_nanofosil = spesies_nanofosil::find($id);
            if (is_null($spesies_nanofosil)){
                return response()->json(['error'=>'Data Not Found!'], 404);
            }

            $zona_geologi = zona_geologi::where('id_spesies', $id);
            $zona_geologi->delete();
            $spesies_nanofosil->delete();
            return response()->json(['success'=>'Delete Data Spesies Nanofosil Success!'], 200);
        }
    }
}
