<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\spesies_nanofosil;
use App\Models\zona_geologi;
use App\Models\umur_geologi;
use App\Exports\SampleExportByRequest;
use Maatwebsite\Excel\Facades\Excel;


class ExcelRequestInputController extends Controller
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
                return response()->json(['error'=>$validator->errors()], 400);
            }

            //memastikan jumlah spesies telah dimasukkan untuk spesies yang telah terdaftar dalam database
            if($request->id_spesies != null) {
                $id_spesies = explode(', ', $request->id_spesies);
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

            return Excel::download(new SampleExportByRequest($request->nama_observer, $request->tanggal_penelitian, $request->lokasi, $request->litologi, $request->formasi, $request->longitude, $request->latitude, $request->kode_sample, $request->kelimpahan, $request->preparasi, $request->pengawetan, $request->tujuan, $request->stopsite, $request->id_spesies, $request->spesies_tambahan, $request->id_spesies_jumlah, $request->spesies_tambahan_jumlah), 'Fossil List Export '.now()->format('Y-m-d H.i.s').'.xlsx');
        }
    }

    public function test(Request $request)
    {
        $observer['nama_observer'] = $request->nama_observer;
        $observer['tanggal_penelitian'] = $request->tanggal_penelitian;
        $studi_area['lokasi'] = $request->lokasi;
        $studi_area['litologi'] = $request->litologi;
        $studi_area['formasi'] = $request->formasi;
        $studi_area['longitude'] = $request->longitude;
        $studi_area['latitude'] = $request->latitude;
        $sample['kode_sample'] = $request->kode_sample;
        $sample['kelimpahan'] = $request->kelimpahan;
        $sample['preparasi'] = $request->preparasi;
        $sample['pengawetan'] = $request->pengawetan;
        $sample['tujuan'] = $request->tujuan;
        $sample['stopsite'] = $request->stopsite;
        if ($request->id_spesies != null) {
            $id_spesies = explode(', ', $request->id_spesies);
        }
        $spesies_nanofosil = Array();
        foreach ($id_spesies as $id_spesies_value) {
            $spesies_nanofosil[] = spesies_nanofosil::where('id_spesies', '=', $id_spesies_value)->get();
            $test_value = spesies_nanofosil::where('id_spesies', '=', $id_spesies_value)->get()->first();
        }
        if ($request->spesies_tambahan != null) {
            $spesies_tambahan = explode(', ', $request->spesies_tambahan);
            foreach ($spesies_tambahan as $spesies_tambahan_value) {
                $spesies_nanofosil[] = [[
                    'id_spesies' => null,
                    'nama_spesies' => $spesies_tambahan_value,
                    'status' => 'tambahan'
                ]];
            }
            // $spesies_nanofosil[] = $spesies_nanofosil_tambahan;
        }
        $umur_condition = Array();
        for ($i=0; $i<count($spesies_nanofosil); $i++) {
            $umur_condition[$i] = [
                'NP1' => false,
                'NP2' => false,
                'NP3' => false,
                'NP4' => false,
                'NP5' => false,
                'NP6' => false,
                'NP7' => false,
                'NP8' => false,
                'NP9' => false,
                'NP10' => false,
                'NP11' => false,
                'NP12' => false,
                'NP13' => false,
                'NP14' => false,
                'NP15' => false,
                'NP16' => false,
                'NP17' => false,
                'NP18' => false,
                'NP19' => false,
                'NP20' => false,
                'NP21' => false,
                'NP22' => false,
                'NP23' => false,
                'NP24' => false,
                'NP25' => false,
                'NN1' => false,
                'NN2' => false,
                'NN3' => false,
                'NN4' => false,
                'NN5' => false,
                'NN6' => false,
                'NN7' => false,
                'NN8' => false,
                'NN9' => false,
                'NN10' => false,
                'NN11' => false,
                'NN12' => false,
                'NN13' => false,
                'NN14' => false,
                'NN15' => false,
                'NN16' => false,
                'NN17' => false,
                'NN18' => false,
                'NN19' => false,
                'NN20' => false,
                'NN21' => false,
            ];
        }
        $count_umur = Array();
        $count_umur = [
            'NP1' => 0,
            'NP2' => 0,
            'NP3' => 0,
            'NP4' => 0,
            'NP5' => 0,
            'NP6' => 0,
            'NP7' => 0,
            'NP8' => 0,
            'NP9' => 0,
            'NP10' => 0,
            'NP11' => 0,
            'NP12' => 0,
            'NP13' => 0,
            'NP14' => 0,
            'NP15' => 0,
            'NP16' => 0,
            'NP17' => 0,
            'NP18' => 0,
            'NP19' => 0,
            'NP20' => 0,
            'NP21' => 0,
            'NP22' => 0,
            'NP23' => 0,
            'NP24' => 0,
            'NP25' => 0,
            'NN1' => 0,
            'NN2' => 0,
            'NN3' => 0,
            'NN4' => 0,
            'NN5' => 0,
            'NN6' => 0,
            'NN7' => 0,
            'NN8' => 0,
            'NN9' => 0,
            'NN10' => 0,
            'NN11' => 0,
            'NN12' => 0,
            'NN13' => 0,
            'NN14' => 0,
            'NN15' => 0,
            'NN16' => 0,
            'NN17' => 0,
            'NN18' => 0,
            'NN19' => 0,
            'NN20' => 0,
            'NN21' => 0,
        ];
        $count_spesies_berumur = 0;
        $i = 0;
        foreach ($spesies_nanofosil as $spesies_nanofosil_value) {
            $spesies_nanofosil_value[0]['zona'] = zona_geologi::where('id_spesies', '=', $spesies_nanofosil_value[0]['id_spesies'])->get();
            if ($spesies_nanofosil_value[0]['zona']->count() != 0) {
                $count_spesies_berumur++;
            }
            foreach ($spesies_nanofosil_value[0]['zona'] as $zona_value) {
                switch ($zona_value['id_umur']) {
                    case 1:
                        $umur_condition[$i]['NP1'] = true;
                        $count_umur['NP1']++;
                        break;
                    case 2:
                        $umur_condition[$i]['NP2'] = true;
                        $count_umur['NP2']++;
                        break;
                    case 3:
                        $umur_condition[$i]['NP3'] = true;
                        $count_umur['NP3']++;
                        break;
                    case 4:
                        $umur_condition[$i]['NP4'] = true;
                        $count_umur['NP4']++;
                        break;
                    case 5:
                        $umur_condition[$i]['NP5'] = true;
                        $count_umur['NP5']++;
                        break;
                    case 6:
                        $umur_condition[$i]['NP6'] = true;
                        $count_umur['NP6']++;
                        break;
                    case 7:
                        $umur_condition[$i]['NP7'] = true;
                        $count_umur['NP7']++;
                        break;
                    case 8:
                        $umur_condition[$i]['NP8'] = true;
                        $count_umur['NP8']++;
                        break;
                    case 9:
                        $umur_condition[$i]['NP9'] = true;
                        $count_umur['NP9']++;
                        break;
                    case 10:
                        $umur_condition[$i]['NP10'] = true;
                        $count_umur['NP10']++;
                        break;
                    case 11:
                        $umur_condition[$i]['NP11'] = true;
                        $count_umur['NP11']++;
                        break;
                    case 12:
                        $umur_condition[$i]['NP12'] = true;
                        $count_umur['NP12']++;
                        break;
                    case 13:
                        $umur_condition[$i]['NP13'] = true;
                        $count_umur['NP13']++;
                        break;
                    case 14:
                        $umur_condition[$i]['NP14'] = true;
                        $count_umur['NP14']++;
                        break;
                    case 15:
                        $umur_condition[$i]['NP15'] = true;
                        $count_umur['NP15']++;
                        break;
                    case 16:
                        $umur_condition[$i]['NP16'] = true;
                        $count_umur['NP16']++;
                        break;
                    case 17:
                        $umur_condition[$i]['NP17'] = true;
                        $count_umur['NP17']++;
                        break;
                    case 18:
                        $umur_condition[$i]['NP18'] = true;
                        $count_umur['NP18']++;
                        break;
                    case 19:
                        $umur_condition[$i]['NP19'] = true;
                        $count_umur['NP19']++;
                        break;
                    case 20:
                        $umur_condition[$i]['NP20'] = true;
                        $count_umur['NP20']++;
                        break;
                    case 21:
                        $umur_condition[$i]['NP21'] = true;
                        $count_umur['NP21']++;
                        break;
                    case 22:
                        $umur_condition[$i]['NP22'] = true;
                        $count_umur['NP22']++;
                        break;
                    case 23:
                        $umur_condition[$i]['NP23'] = true;
                        $count_umur['NP23']++;
                        break;
                    case 24:
                        $umur_condition[$i]['NP24'] = true;
                        $count_umur['NP24']++;
                        break;
                    case 25:
                        $umur_condition[$i]['NP25'] = true;
                        $count_umur['NP25']++;
                        break;
                    case 26:
                        $umur_condition[$i]['NN1'] = true;
                        $count_umur['NN1']++;
                        break;
                    case 27:
                        $umur_condition[$i]['NN2'] = true;
                        $count_umur['NN2']++;
                        break;
                    case 28:
                        $umur_condition[$i]['NN3'] = true;
                        $count_umur['NN3']++;
                        break;
                    case 29:
                        $umur_condition[$i]['NN4'] = true;
                        $count_umur['NN4']++;
                        break;
                    case 30:
                        $umur_condition[$i]['NN5'] = true;
                        $count_umur['NN5']++;
                        break;
                    case 31:
                        $umur_condition[$i]['NN6'] = true;
                        $count_umur['NN6']++;
                        break;
                    case 32:
                        $umur_condition[$i]['NN7'] = true;
                        $count_umur['NN7']++;
                        break;
                    case 33:
                        $umur_condition[$i]['NN8'] = true;
                        $count_umur['NN8']++;
                        break;
                    case 34:
                        $umur_condition[$i]['NN9'] = true;
                        $count_umur['NN9']++;
                        break;
                    case 35:
                        $umur_condition[$i]['NN10'] = true;
                        $count_umur['NN10']++;
                        break;
                    case 36:
                        $umur_condition[$i]['NN11'] = true;
                        $count_umur['NN11']++;
                        break;
                    case 37:
                        $umur_condition[$i]['NN12'] = true;
                        $count_umur['NN12']++;
                        break;
                    case 38:
                        $umur_condition[$i]['NN13'] = true;
                        $count_umur['NN13']++;
                        break;
                    case 39:
                        $umur_condition[$i]['NN14'] = true;
                        $count_umur['NN14']++;
                        break;
                    case 40:
                        $umur_condition[$i]['NN15'] = true;
                        $count_umur['NN15']++;
                        break;
                    case 41:
                        $umur_condition[$i]['NN16'] = true;
                        $count_umur['NN16']++;
                        break;
                    case 42:
                        $umur_condition[$i]['NN17'] = true;
                        $count_umur['NN17']++;
                        break;
                    case 43:
                        $umur_condition[$i]['NN18'] = true;
                        $count_umur['NN18']++;
                        break;
                    case 44:
                        $umur_condition[$i]['NN19'] = true;
                        $count_umur['NN19']++;
                        break;
                    case 45:
                        $umur_condition[$i]['NN20'] = true;
                        $count_umur['NN20']++;
                        break;
                    case 46:
                        $umur_condition[$i]['NN21'] = true;
                        $count_umur['NN21']++;
                        break;
                }
            }
        $i++;
        }

        $max_count_umur = max($count_umur);
        $kesimpulan = array();
        if ($max_count_umur == $count_spesies_berumur && $count_spesies_berumur != 0) {
            $kesimpulan['rentang_zona'] = array_keys($count_umur, $max_count_umur);
            $kesimpulan['min_zona'] = $kesimpulan['rentang_zona'][0];
            $kesimpulan['max_zona'] = $kesimpulan['rentang_zona'][count($kesimpulan['rentang_zona']) - 1];
            $kesimpulan['min_umur'] = umur_geologi::where('zona_geo', '=', $kesimpulan['min_zona'])->get()->first()['umur_geo'];
            $kesimpulan['max_umur'] = umur_geologi::where('zona_geo', '=', $kesimpulan['max_zona'])->get()->first()['umur_geo'];
            $kesimpulan['min_umur_kata_per_kata'] = explode(' ', $kesimpulan['min_umur']);
            $kesimpulan['max_umur_kata_per_kata'] = explode(' ', $kesimpulan['max_umur']);
        }
        elseif ($max_count_umur != $count_spesies_berumur) {
            $j = 0;
            foreach ($spesies_nanofosil as $spesies_nanofosil_value) {
                $spesies_nanofosil_value[0]->zona = zona_geologi::where('id_spesies', '=', $spesies_nanofosil_value[0]->id_spesies)->get();
                $k = 0;
                if ($spesies_nanofosil_value[0]->zona->count() != 0) {
                    foreach ($spesies_nanofosil_value[0]->zona as $zona_value) {
                        $rentang_umur_spesies[$j][$k] = $zona_value['id_umur'];
                        $k++;
                    }
                $min_umur_spesies[$j] = $rentang_umur_spesies[$j][0];
                $max_umur_spesies[$j] = $rentang_umur_spesies[$j][count($rentang_umur_spesies[$j]) - 1];
                $j++;
                }
            }
            $kesimpulan['min_zona'] = umur_geologi::where('id_umur', '=', min($max_umur_spesies))->get()->first()['zona_geo'];
            $kesimpulan['max_zona'] = umur_geologi::where('id_umur', '=', max($min_umur_spesies))->get()->first()['zona_geo'];
            $kesimpulan['min_umur'] = umur_geologi::where('id_umur', '=', min($max_umur_spesies))->get()->first()['umur_geo'];
            $kesimpulan['max_umur'] = umur_geologi::where('id_umur', '=', max($min_umur_spesies))->get()->first()['umur_geo'];
            $kesimpulan['min_umur_kata_per_kata'] = explode(' ', $kesimpulan['min_umur']);
            $kesimpulan['max_umur_kata_per_kata'] = explode(' ', $kesimpulan['max_umur']);
        }

        $sample_detail = [
            'sample' => $sample,
            'studi_area' => $studi_area,
            'observer' => $observer,
            // 'sample_spesies' => $sample_spesies,
            'spesies_nanofosil' => $spesies_nanofosil,
            'test_value' => $test_value,
            'count_spesies' => count($spesies_nanofosil),
            'umur_condition' => $umur_condition,
            'count_spesies_berumur' => $count_spesies_berumur,
            'count_umur' => $count_umur,
            'max_count_umur' => $max_count_umur,
            'kesimpulan' => $kesimpulan,
            // // 'rentang_umur_spesies' => $rentang_umur_spesies,
            // '$min_umur_spesies' => $min_umur_spesies,
            // '$max_umur_spesies' => $max_umur_spesies,
            // 'min_umur_geo' => $min_umur_geo,
            // 'max_umur_geo' => $max_umur_geo
        ];

        return response()->json($sample_detail, 200);
    }
}
