<?php

namespace App\Http\Controllers;
use App\Models\sample;
use App\Models\sample_spesies;
use App\Models\spesies_nanofosil;
use App\Models\zona_geologi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class TerimaSampelController extends Controller
{
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if ($user['role'] != 'admin') {
            return response()->json(['error'=>'Unauthorised'], 403);
        } else {
            $sample = sample::find($id);
            if (is_null($sample)){
                return response()->json(['error'=>'Data Not Found!'], 404);
            }

            sample::where('id_sample', '=', $id)->update([
                'status' => 'diterima',
                'tanggal_diterima' => Carbon::now()->toDateString()
            ]);

            $sample_spesies = sample_spesies::where('id_sample', '=', $sample['id_sample'])->get();
            foreach ($sample_spesies as $sample_spesies_value) {
                $spesies = spesies_nanofosil::find($sample_spesies_value['id_spesies']);
                if ($spesies['status'] == 'tambahan') {
                    $spesies_terverifikasi_duplikat = spesies_nanofosil::where('nama_spesies', '=', $spesies['nama_spesies'])
                    ->where('status', '=', 'terverifikasi')
                    ->get();
                    $hit = $spesies_terverifikasi_duplikat->count();

                    if ($hit > 0) {
                        $id_spesies_duplikat = $sample_spesies_value['id_spesies'];
                        //mengganti id_spesies sample_spesies dari spesies tambahan duplikat dengan id_spesies spesies terverifikasi
                        $sample_spesies_value->update([
                            'id_spesies' => $spesies_terverifikasi_duplikat->first()['id_spesies']
                        ]);

                        //menghapus record pada tabel database zona_geologi dan spesies_nannofosil yang berhubungan dengan id_spesies spesies tambahan duplikat
                        $zona_geologi = zona_geologi::where('id_spesies', $id_spesies_duplikat);
                        $zona_geologi->delete();
                        $spesies->delete();
                    } else {
                        $spesies->update([
                            'status' => 'terverifikasi'
                        ]);
                    }
                }
            }

            return response()->json(['success' => 'Data successfully updated'], 200);
        }
    }
}
