<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Models\spesies_nanofosil;
use App\Models\zona_geologi;
use App\Models\sample_spesies;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpesiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //menampilkan daftar spesies nannofosil terverifikasi
    public function index() {
        $daftarSpesiesTerverifikasi = spesies_nanofosil::where('status', '=', 'terverifikasi')->get();

        return response()->json($daftarSpesiesTerverifikasi, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //menambahkan spesies nannofosil terverifikasi baru
    public function store(Request $request)
    {
        //mengambil data user
        $user = Auth::user();

        //memastikan user memiliki role admin
        if ($user['role'] != 'admin') {
            return response()->json(['error'=>'Unauthorised'], 403);
        } else {
            //memastikan spesies terverifikasi yang akan disimpan sudah ada di database atau belum
            $hit = spesies_nanofosil::where('nama_spesies', '=', $request->nama_spesies)->where('status', '=', 'terverifikasi')->get();
            $hit = $hit->count();
            if ($hit > 0) {
                return response()->json(['error'=>'Spesies already exists!'], 409);
            } else {
                //memastikan semua isian request yang dibutuhkan terisi dan sesuai
                $validator = Validator::make($request->all(), [
                    'nama_spesies' => 'required',
                    'id_umur_awal' => 'required|integer|between:1, 46',
                    'id_umur_akhir' => 'required|integer|between:1, 46'
                ]);

                if ($validator->fails()) {
                    return response()->json(['error'=>$validator->errors()], 400);
                }

                //memastikan request id_umur_awal tidak lebih dari id_umur_akhir
                if ($request->id_umur_awal > $request->id_umur_akhir) {
                    return response()->json(['error' => 'Umur awal melebihi umur akhir'], 400);
                }

                //mengumpulkan seluruh request
                $input = $request->all();

                //menyimpan record nama_spesies dan status baru ke tabel database 'spesies_nanofosil'
                $spesies_nanofosil = spesies_nanofosil::create([
                    'nama_spesies' => $input['nama_spesies'],
                    'status' => 'terverifikasi'
                ]);

                //mengumpulkan data untuk record table database zona_geologi
                $input_zona_geologi['id_spesies'] = $spesies_nanofosil['id_spesies'];
                $umur_awal = $input['id_umur_awal'];
                $umur_akhir= $input['id_umur_akhir'];

                //menyimpan record id_spesies dan id_umur dari spesies terverifikasi baru ke tabel database 'zona_geologi'
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
    //menampilkan salah satu spesies nannofosil beserta id-id umurnya
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
    //mengedit suatu spesies nannofosil
    public function update(Request $request, $id)
    {
        //mengambil data user
        $user = Auth::user();

        //memastikan user memiliki role admin
        if ($user['role'] != 'admin') {
            return response()->json(['error'=>'Unauthorised'], 403);
        } else {
            //memeriksa apakah id_spesies request ada di database
            $spesies_nanofosil = spesies_nanofosil::find($id);
            if (is_null($spesies_nanofosil)){
                return response()->json(['error'=>'Data Not Found!'], 404);
            }

            //memastikan semua isian request yang dibutuhkan terisi dan sesuai
            $validator = Validator::make($request->all(), [
                'nama_spesies' => 'required',
                'id_umur_awal' => 'required|integer|between:1, 46',
                'id_umur_akhir' => 'required|integer|between:1, 46'
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 400);
            }

            //memastikan spesies terverifikasi yang akan disimpan sudah ada di database atau belum
		    $hit = spesies_nanofosil::where('nama_spesies', '=', $request->nama_spesies)->where('status', '=', 'terverifikasi')->get();
            $hit = $hit->count();
            if ($hit > 0 && strtolower($spesies_nanofosil['nama_spesies']) != strtolower($request->nama_spesies)) {
                return response()->json(['error'=>'Spesies already exists!'], 409);
            } else {
                //memastikan request id_umur_awal tidak lebih dari id_umur_akhir
                if ($request->id_umur_awal > $request->id_umur_akhir) {
                    return response()->json(['error' => 'Umur awal melebihi umur akhir'], 400);
                }

                //mengumpulkan seluruh request
                $input = $request->all();

                //mengedit record nama_spesies pada tabel database 'spesies_nanofosil' sesuai input id
                spesies_nanofosil::where('id_spesies', $id)->update([
                    'nama_spesies' => $input['nama_spesies']
                ]);

                //menghapus zona_geologi lama dari spesies yang diedit
                zona_geologi::where('id_spesies', '=', $id)->delete();

                //mengumpulkan data untuk record table database zona_geologi
                $input_zona_geologi['id_spesies'] = $id;
                $umur_awal = $input['id_umur_awal'];
                $umur_akhir= $input['id_umur_akhir'];

                //menyimpan record id_spesies dan id_umur dari spesies yang diedit ke tabel database 'zona_geologi'
                for ($i = $umur_awal; $i <= $umur_akhir; $i++) {
                    $input_zona_geologi['id_umur'] = $i;
                    zona_geologi::create([
                        'id_spesies' => $input_zona_geologi['id_spesies'],
                        'id_umur' => $input_zona_geologi['id_umur']]);
                }

                return response()->json(['success' => 'Data successfully updated'], 200);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //menghapus suatu spesies nannofosil
    public function destroy($id)
    {
        //mengambil data user
        $user = Auth::user();

        //memastikan user memiliki role admin
        if ($user['role'] != 'admin') {
            return response()->json(['error'=>'Unauthorised'], 403);
        } else {
            //memeriksa apakah id_spesies request ada di database
            $spesies_nanofosil = spesies_nanofosil::find($id);
            if (is_null($spesies_nanofosil)){
                return response()->json(['error'=>'Data Not Found!'], 404);
            } else {
                //menghapus record pada tabel database sample_spesies yang berhubungan dengan id_spesies request
                $sample_spesies = sample_spesies::where('id_spesies', $id);
                $sample_spesies->delete();

                //menghapus record pada tabel database zona_geologi dan spesies_nannofosil yang berhubungan dengan id_spesies request
                $zona_geologi = zona_geologi::where('id_spesies', $id);
                $zona_geologi->delete();
                $spesies_nanofosil->delete();
                return response()->json(['success'=>'Delete Data Spesies Nanofosil Success!'], 200);
            }
        }
    }
}

