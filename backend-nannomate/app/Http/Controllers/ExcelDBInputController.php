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

    public function test($id)
    {
        $sample = sample::find($id);
        $studi_area = studi_area::where('id_studi_area', '=', $sample['id_studi_area'])->get()->first();
        $observer = observer::where('id_observer', '=', $studi_area['id_observer'])->get()->first();
        $sample_spesies = sample_spesies::where('id_sample', '=', $sample['id_sample'])->get();
        $spesies_nanofosil = Array();
        $hit = $sample_spesies->count();
        if ($hit != 0) {
            foreach ($sample_spesies as $sample_spesies_value) {
                $spesies_nanofosil[] = spesies_nanofosil::where('id_spesies', '=', $sample_spesies_value['id_spesies'])->get();
            }
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
        // $count_umur = Array();
        // $count_umur = [
        //     'NP1' => 0,
        //     'NP2' => 0,
        //     'NP3' => 0,
        //     'NP4' => 0,
        //     'NP5' => 0,
        //     'NP6' => 0,
        //     'NP7' => 0,
        //     'NP8' => 0,
        //     'NP9' => 0,
        //     'NP10' => 0,
        //     'NP11' => 0,
        //     'NP12' => 0,
        //     'NP13' => 0,
        //     'NP14' => 0,
        //     'NP15' => 0,
        //     'NP16' => 0,
        //     'NP17' => 0,
        //     'NP18' => 0,
        //     'NP19' => 0,
        //     'NP20' => 0,
        //     'NP21' => 0,
        //     'NP22' => 0,
        //     'NP23' => 0,
        //     'NP24' => 0,
        //     'NP25' => 0,
        //     'NN1' => 0,
        //     'NN2' => 0,
        //     'NN3' => 0,
        //     'NN4' => 0,
        //     'NN5' => 0,
        //     'NN6' => 0,
        //     'NN7' => 0,
        //     'NN8' => 0,
        //     'NN9' => 0,
        //     'NN10' => 0,
        //     'NN11' => 0,
        //     'NN12' => 0,
        //     'NN13' => 0,
        //     'NN14' => 0,
        //     'NN15' => 0,
        //     'NN16' => 0,
        //     'NN17' => 0,
        //     'NN18' => 0,
        //     'NN19' => 0,
        //     'NN20' => 0,
        //     'NN21' => 0,
        // ];
        // $count_spesies_berumur = 0;
        $i = 0;
        foreach ($spesies_nanofosil as $spesies_nanofosil_value) {
            $spesies_nanofosil_value[0]->zona = zona_geologi::where('id_spesies', '=', $spesies_nanofosil_value[0]->id_spesies)->get();
            // if ($spesies_nanofosil_value[0]->zona->count() != 0) {
            //     $count_spesies_berumur++;
            // }
            $j = 0;
            foreach ($spesies_nanofosil_value[0]->zona as $zona_value) {
                switch ($zona_value['id_umur']) {
                    case 1:
                        $umur_condition[$i]['NP1'] = true;
                        // $count_umur['NP1']++;
                        break;
                    case 2:
                        $umur_condition[$i]['NP2'] = true;
                        // $count_umur['NP2']++;
                        break;
                    case 3:
                        $umur_condition[$i]['NP3'] = true;
                        // $count_umur['NP3']++;
                        break;
                    case 4:
                        $umur_condition[$i]['NP4'] = true;
                        // $count_umur['NP4']++;
                        break;
                    case 5:
                        $umur_condition[$i]['NP5'] = true;
                        // $count_umur['NP5']++;
                        break;
                    case 6:
                        $umur_condition[$i]['NP6'] = true;
                        // $count_umur['NP6']++;
                        break;
                    case 7:
                        $umur_condition[$i]['NP7'] = true;
                        // $count_umur['NP7']++;
                        break;
                    case 8:
                        $umur_condition[$i]['NP8'] = true;
                        // $count_umur['NP8']++;
                        break;
                    case 9:
                        $umur_condition[$i]['NP9'] = true;
                        // $count_umur['NP9']++;
                        break;
                    case 10:
                        $umur_condition[$i]['NP10'] = true;
                        // $count_umur['NP10']++;
                        break;
                    case 11:
                        $umur_condition[$i]['NP11'] = true;
                        // $count_umur['NP11']++;
                        break;
                    case 12:
                        $umur_condition[$i]['NP12'] = true;
                        // $count_umur['NP12']++;
                        break;
                    case 13:
                        $umur_condition[$i]['NP13'] = true;
                        // $count_umur['NP13']++;
                        break;
                    case 14:
                        $umur_condition[$i]['NP14'] = true;
                        // $count_umur['NP14']++;
                        break;
                    case 15:
                        $umur_condition[$i]['NP15'] = true;
                        // $count_umur['NP15']++;
                        break;
                    case 16:
                        $umur_condition[$i]['NP16'] = true;
                        // $count_umur['NP16']++;
                        break;
                    case 17:
                        $umur_condition[$i]['NP17'] = true;
                        // $count_umur['NP17']++;
                        break;
                    case 18:
                        $umur_condition[$i]['NP18'] = true;
                        // $count_umur['NP18']++;
                        break;
                    case 19:
                        $umur_condition[$i]['NP19'] = true;
                        // $count_umur['NP19']++;
                        break;
                    case 20:
                        $umur_condition[$i]['NP20'] = true;
                        // $count_umur['NP20']++;
                        break;
                    case 21:
                        $umur_condition[$i]['NP21'] = true;
                        // $count_umur['NP21']++;
                        break;
                    case 22:
                        $umur_condition[$i]['NP22'] = true;
                        // $count_umur['NP22']++;
                        break;
                    case 23:
                        $umur_condition[$i]['NP23'] = true;
                        // $count_umur['NP23']++;
                        break;
                    case 24:
                        $umur_condition[$i]['NP24'] = true;
                        // $count_umur['NP24']++;
                        break;
                    case 25:
                        $umur_condition[$i]['NP25'] = true;
                        // $count_umur['NP25']++;
                        break;
                    case 26:
                        $umur_condition[$i]['NN1'] = true;
                        // $count_umur['NN1']++;
                        break;
                    case 27:
                        $umur_condition[$i]['NN2'] = true;
                        // $count_umur['NN2']++;
                        break;
                    case 28:
                        $umur_condition[$i]['NN3'] = true;
                        // $count_umur['NN3']++;
                        break;
                    case 29:
                        $umur_condition[$i]['NN4'] = true;
                        // $count_umur['NN4']++;
                        break;
                    case 30:
                        $umur_condition[$i]['NN5'] = true;
                        // $count_umur['NN5']++;
                        break;
                    case 31:
                        $umur_condition[$i]['NN6'] = true;
                        // $count_umur['NN6']++;
                        break;
                    case 32:
                        $umur_condition[$i]['NN7'] = true;
                        // $count_umur['NN7']++;
                        break;
                    case 33:
                        $umur_condition[$i]['NN8'] = true;
                        // $count_umur['NN8']++;
                        break;
                    case 34:
                        $umur_condition[$i]['NN9'] = true;
                        // $count_umur['NN9']++;
                        break;
                    case 35:
                        $umur_condition[$i]['NN10'] = true;
                        // $count_umur['NN10']++;
                        break;
                    case 36:
                        $umur_condition[$i]['NN11'] = true;
                        // $count_umur['NN11']++;
                        break;
                    case 37:
                        $umur_condition[$i]['NN12'] = true;
                        // $count_umur['NN12']++;
                        break;
                    case 38:
                        $umur_condition[$i]['NN13'] = true;
                        // $count_umur['NN13']++;
                        break;
                    case 39:
                        $umur_condition[$i]['NN14'] = true;
                        // $count_umur['NN14']++;
                        break;
                    case 40:
                        $umur_condition[$i]['NN15'] = true;
                        // $count_umur['NN15']++;
                        break;
                    case 41:
                        $umur_condition[$i]['NN16'] = true;
                        // $count_umur['NN16']++;
                        break;
                    case 42:
                        $umur_condition[$i]['NN17'] = true;
                        // $count_umur['NN17']++;
                        break;
                    case 43:
                        $umur_condition[$i]['NN18'] = true;
                        // $count_umur['NN18']++;
                        break;
                    case 44:
                        $umur_condition[$i]['NN19'] = true;
                        // $count_umur['NN19']++;
                        break;
                    case 45:
                        $umur_condition[$i]['NN20'] = true;
                        // $count_umur['NN20']++;
                        break;
                    case 46:
                        $umur_condition[$i]['NN21'] = true;
                        // $count_umur['NN21']++;
                        break;
                }
                $umur_spesies[$i][$j] = $zona_value['id_umur'];
                $j++;
            }
        $i++;
        }

        //mengumpulkan umur awal spesies
        for ($k=0; $k<count($umur_spesies); $k++) {
            $umur_awal_spesies[$k] = $umur_spesies[$k][0];
        }

        $max_umur_awal_spesies = max($umur_awal_spesies);

        //mengumpulkan umur akhir spesies yang memiliki overlap dengan spesies termuda
        for ($l=0; $l<count($umur_spesies); $l++) {
            if ($umur_spesies[$l][count($umur_spesies[$l]) - 1] >= $max_umur_awal_spesies) {
                $umur_akhir_spesies[$l] = $umur_spesies[$l][count($umur_spesies[$l]) - 1];
            }
        }

        $min_umur_akhir_spesies = min($umur_akhir_spesies);

        //define kesimpulan
        $kesimpulan = array();
        $kesimpulan['min_zona'] = null;
        $kesimpulan['max_zona'] = null;
        $kesimpulan['min_umur'] = null;
        $kesimpulan['max_umur'] = null;
        $kesimpulan['min_umur_kata_per_kata'] = null;
        $kesimpulan['max_umur_kata_per_kata'] = null;

        //input kesimpulan
        $kesimpulan['min_zona'] = umur_geologi::where('id_umur', '=', $max_umur_awal_spesies)->get()->first()['zona_geo'];
        $kesimpulan['max_zona'] = umur_geologi::where('id_umur', '=', $min_umur_akhir_spesies)->get()->first()['zona_geo'];
        $kesimpulan['min_umur'] = umur_geologi::where('id_umur', '=', $max_umur_awal_spesies)->get()->first()['umur_geo'];
        $kesimpulan['max_umur'] = umur_geologi::where('id_umur', '=', $min_umur_akhir_spesies)->get()->first()['umur_geo'];
        $kesimpulan['min_umur_kata_per_kata'] = explode(' ', $kesimpulan['min_umur']);
        $kesimpulan['max_umur_kata_per_kata'] = explode(' ', $kesimpulan['max_umur']);

        // $sample = sample::find($id);
        // $studi_area = studi_area::where('id_studi_area', '=', $sample['id_studi_area'])->get()->first();
        // $observer = observer::where('id_observer', '=', $studi_area['id_observer'])->get()->first();
        // $sample_spesies = sample_spesies::where('id_sample', '=', $sample['id_sample'])->get();
        // $spesies_nanofosil = Array();
        // foreach ($sample_spesies as $sample_spesies_value) {
        //     $spesies_nanofosil[] = spesies_nanofosil::where('id_spesies', '=', $sample_spesies_value['id_spesies'])->get();
        // }
        // $umur_condition = Array();
        // for ($i=0; $i<count($spesies_nanofosil); $i++) {
        //     $umur_condition[$i] = [
        //         'NP1' => false,
        //         'NP2' => false,
        //         'NP3' => false,
        //         'NP4' => false,
        //         'NP5' => false,
        //         'NP6' => false,
        //         'NP7' => false,
        //         'NP8' => false,
        //         'NP9' => false,
        //         'NP10' => false,
        //         'NP11' => false,
        //         'NP12' => false,
        //         'NP13' => false,
        //         'NP14' => false,
        //         'NP15' => false,
        //         'NP16' => false,
        //         'NP17' => false,
        //         'NP18' => false,
        //         'NP19' => false,
        //         'NP20' => false,
        //         'NP21' => false,
        //         'NP22' => false,
        //         'NP23' => false,
        //         'NP24' => false,
        //         'NP25' => false,
        //         'NN1' => false,
        //         'NN2' => false,
        //         'NN3' => false,
        //         'NN4' => false,
        //         'NN5' => false,
        //         'NN6' => false,
        //         'NN7' => false,
        //         'NN8' => false,
        //         'NN9' => false,
        //         'NN10' => false,
        //         'NN11' => false,
        //         'NN12' => false,
        //         'NN13' => false,
        //         'NN14' => false,
        //         'NN15' => false,
        //         'NN16' => false,
        //         'NN17' => false,
        //         'NN18' => false,
        //         'NN19' => false,
        //         'NN20' => false,
        //         'NN21' => false,
        //     ];
        // }
        // $count_umur = Array();
        // $count_umur = [
        //     'NP1' => 0,
        //     'NP2' => 0,
        //     'NP3' => 0,
        //     'NP4' => 0,
        //     'NP5' => 0,
        //     'NP6' => 0,
        //     'NP7' => 0,
        //     'NP8' => 0,
        //     'NP9' => 0,
        //     'NP10' => 0,
        //     'NP11' => 0,
        //     'NP12' => 0,
        //     'NP13' => 0,
        //     'NP14' => 0,
        //     'NP15' => 0,
        //     'NP16' => 0,
        //     'NP17' => 0,
        //     'NP18' => 0,
        //     'NP19' => 0,
        //     'NP20' => 0,
        //     'NP21' => 0,
        //     'NP22' => 0,
        //     'NP23' => 0,
        //     'NP24' => 0,
        //     'NP25' => 0,
        //     'NN1' => 0,
        //     'NN2' => 0,
        //     'NN3' => 0,
        //     'NN4' => 0,
        //     'NN5' => 0,
        //     'NN6' => 0,
        //     'NN7' => 0,
        //     'NN8' => 0,
        //     'NN9' => 0,
        //     'NN10' => 0,
        //     'NN11' => 0,
        //     'NN12' => 0,
        //     'NN13' => 0,
        //     'NN14' => 0,
        //     'NN15' => 0,
        //     'NN16' => 0,
        //     'NN17' => 0,
        //     'NN18' => 0,
        //     'NN19' => 0,
        //     'NN20' => 0,
        //     'NN21' => 0,
        // ];
        // $count_spesies_berumur = 0;
        // $i = 0;
        // foreach ($spesies_nanofosil as $spesies_nanofosil_value) {
        //     $spesies_nanofosil_value[0]->zona = zona_geologi::where('id_spesies', '=', $spesies_nanofosil_value[0]->id_spesies)->get();
        //     // $test_value = $spesies_nanofosil_value[0]->zona;
        //     if ($spesies_nanofosil_value[0]->zona->count() != 0) {
        //         $count_spesies_berumur++;
        //     }
        //     foreach ($spesies_nanofosil_value[0]->zona as $zona_value) {
        //         switch ($zona_value['id_umur']) {
        //             case 1:
        //                 $umur_condition[$i]['NP1'] = true;
        //                 $count_umur['NP1']++;
        //                 break;
        //             case 2:
        //                 $umur_condition[$i]['NP2'] = true;
        //                 $count_umur['NP2']++;
        //                 break;
        //             case 3:
        //                 $umur_condition[$i]['NP3'] = true;
        //                 $count_umur['NP3']++;
        //                 break;
        //             case 4:
        //                 $umur_condition[$i]['NP4'] = true;
        //                 $count_umur['NP4']++;
        //                 break;
        //             case 5:
        //                 $umur_condition[$i]['NP5'] = true;
        //                 $count_umur['NP5']++;
        //                 break;
        //             case 6:
        //                 $umur_condition[$i]['NP6'] = true;
        //                 $count_umur['NP6']++;
        //                 break;
        //             case 7:
        //                 $umur_condition[$i]['NP7'] = true;
        //                 $count_umur['NP7']++;
        //                 break;
        //             case 8:
        //                 $umur_condition[$i]['NP8'] = true;
        //                 $count_umur['NP8']++;
        //                 break;
        //             case 9:
        //                 $umur_condition[$i]['NP9'] = true;
        //                 $count_umur['NP9']++;
        //                 break;
        //             case 10:
        //                 $umur_condition[$i]['NP10'] = true;
        //                 $count_umur['NP10']++;
        //                 break;
        //             case 11:
        //                 $umur_condition[$i]['NP11'] = true;
        //                 $count_umur['NP11']++;
        //                 break;
        //             case 12:
        //                 $umur_condition[$i]['NP12'] = true;
        //                 $count_umur['NP12']++;
        //                 break;
        //             case 13:
        //                 $umur_condition[$i]['NP13'] = true;
        //                 $count_umur['NP13']++;
        //                 break;
        //             case 14:
        //                 $umur_condition[$i]['NP14'] = true;
        //                 $count_umur['NP14']++;
        //                 break;
        //             case 15:
        //                 $umur_condition[$i]['NP15'] = true;
        //                 $count_umur['NP15']++;
        //                 break;
        //             case 16:
        //                 $umur_condition[$i]['NP16'] = true;
        //                 $count_umur['NP16']++;
        //                 break;
        //             case 17:
        //                 $umur_condition[$i]['NP17'] = true;
        //                 $count_umur['NP17']++;
        //                 break;
        //             case 18:
        //                 $umur_condition[$i]['NP18'] = true;
        //                 $count_umur['NP18']++;
        //                 break;
        //             case 19:
        //                 $umur_condition[$i]['NP19'] = true;
        //                 $count_umur['NP19']++;
        //                 break;
        //             case 20:
        //                 $umur_condition[$i]['NP20'] = true;
        //                 $count_umur['NP20']++;
        //                 break;
        //             case 21:
        //                 $umur_condition[$i]['NP21'] = true;
        //                 $count_umur['NP21']++;
        //                 break;
        //             case 22:
        //                 $umur_condition[$i]['NP22'] = true;
        //                 $count_umur['NP22']++;
        //                 break;
        //             case 23:
        //                 $umur_condition[$i]['NP23'] = true;
        //                 $count_umur['NP23']++;
        //                 break;
        //             case 24:
        //                 $umur_condition[$i]['NP24'] = true;
        //                 $count_umur['NP24']++;
        //                 break;
        //             case 25:
        //                 $umur_condition[$i]['NP25'] = true;
        //                 $count_umur['NP25']++;
        //                 break;
        //             case 26:
        //                 $umur_condition[$i]['NN1'] = true;
        //                 $count_umur['NN1']++;
        //                 break;
        //             case 27:
        //                 $umur_condition[$i]['NN2'] = true;
        //                 $count_umur['NN2']++;
        //                 break;
        //             case 28:
        //                 $umur_condition[$i]['NN3'] = true;
        //                 $count_umur['NN3']++;
        //                 break;
        //             case 29:
        //                 $umur_condition[$i]['NN4'] = true;
        //                 $count_umur['NN4']++;
        //                 break;
        //             case 30:
        //                 $umur_condition[$i]['NN5'] = true;
        //                 $count_umur['NN5']++;
        //                 break;
        //             case 31:
        //                 $umur_condition[$i]['NN6'] = true;
        //                 $count_umur['NN6']++;
        //                 break;
        //             case 32:
        //                 $umur_condition[$i]['NN7'] = true;
        //                 $count_umur['NN7']++;
        //                 break;
        //             case 33:
        //                 $umur_condition[$i]['NN8'] = true;
        //                 $count_umur['NN8']++;
        //                 break;
        //             case 34:
        //                 $umur_condition[$i]['NN9'] = true;
        //                 $count_umur['NN9']++;
        //                 break;
        //             case 35:
        //                 $umur_condition[$i]['NN10'] = true;
        //                 $count_umur['NN10']++;
        //                 break;
        //             case 36:
        //                 $umur_condition[$i]['NN11'] = true;
        //                 $count_umur['NN11']++;
        //                 break;
        //             case 37:
        //                 $umur_condition[$i]['NN12'] = true;
        //                 $count_umur['NN12']++;
        //                 break;
        //             case 38:
        //                 $umur_condition[$i]['NN13'] = true;
        //                 $count_umur['NN13']++;
        //                 break;
        //             case 39:
        //                 $umur_condition[$i]['NN14'] = true;
        //                 $count_umur['NN14']++;
        //                 break;
        //             case 40:
        //                 $umur_condition[$i]['NN15'] = true;
        //                 $count_umur['NN15']++;
        //                 break;
        //             case 41:
        //                 $umur_condition[$i]['NN16'] = true;
        //                 $count_umur['NN16']++;
        //                 break;
        //             case 42:
        //                 $umur_condition[$i]['NN17'] = true;
        //                 $count_umur['NN17']++;
        //                 break;
        //             case 43:
        //                 $umur_condition[$i]['NN18'] = true;
        //                 $count_umur['NN18']++;
        //                 break;
        //             case 44:
        //                 $umur_condition[$i]['NN19'] = true;
        //                 $count_umur['NN19']++;
        //                 break;
        //             case 45:
        //                 $umur_condition[$i]['NN20'] = true;
        //                 $count_umur['NN20']++;
        //                 break;
        //             case 46:
        //                 $umur_condition[$i]['NN21'] = true;
        //                 $count_umur['NN21']++;
        //                 break;
        //         }
        //     }
        // $i++;
        // }

        // $zona_awal_seluruh_spesies = [2,3];
        // $max_count_umur = max($count_umur);
        // // $count_spesies_berumur = 0;
        // // foreach ($spesies_nanofosil as $spesies_nanofosil_value) {
        // //     if ($spesies_nanofosil_value[0]->zona) {
        // //         $count_spesies_berumur++;
        // //     }
        // // }
        // $kesimpulan = array();
        // if ($max_count_umur == $count_spesies_berumur && $count_spesies_berumur != 0) {
        //     $kesimpulan['rentang_zona'] = array_keys($count_umur, $max_count_umur);
        //     $kesimpulan['min_zona'] = $kesimpulan['rentang_zona'][0];
        //     $kesimpulan['max_zona'] = $kesimpulan['rentang_zona'][count($kesimpulan['rentang_zona']) - 1];
        //     $kesimpulan['min_umur'] = umur_geologi::where('zona_geo', '=', $kesimpulan['min_zona'])->get()->first()['umur_geo'];
        //     $kesimpulan['max_umur'] = umur_geologi::where('zona_geo', '=', $kesimpulan['max_zona'])->get()->first()['umur_geo'];
        //     $kesimpulan['min_umur_kata_per_kata'] = explode(' ', $kesimpulan['min_umur']);
        //     $kesimpulan['max_umur_kata_per_kata'] = explode(' ', $kesimpulan['max_umur']);
        //     // $kesimpulan['test1'] = $kesimpulan['min_umur_kata_per_kata'][0];
        //     // $kesimpulan['test2'] = $kesimpulan['min_umur_kata_per_kata'][1];
        //     // $kesimpulan['test3'] = $kesimpulan['max_umur_kata_per_kata'][0];
        //     // $kesimpulan['test4'] = $kesimpulan['max_umur_kata_per_kata'][1];
        // }

        // elseif ($max_count_umur != $count_spesies_berumur) {
        //     $j = 0;
        //     foreach ($spesies_nanofosil as $spesies_nanofosil_value) {
        //         $spesies_nanofosil_value[0]->zona = zona_geologi::where('id_spesies', '=', $spesies_nanofosil_value[0]->id_spesies)->get();
        //         $k = 0;
        //         if ($spesies_nanofosil_value[0]->zona->count() != 0) {
        //             foreach ($spesies_nanofosil_value[0]->zona as $zona_value) {
        //                 $rentang_umur_spesies[$j][$k] = $zona_value['id_umur'];
        //                 $k++;
        //             }
        //         $min_umur_spesies[$j] = $rentang_umur_spesies[$j][0];
        //         $max_umur_spesies[$j] = $rentang_umur_spesies[$j][count($rentang_umur_spesies[$j]) - 1];
        //         $j++;
        //         }
        //     }
        //     // $min_umur_geo = min($max_umur_spesies);
        //     // $max_umur_geo = max($min_umur_spesies);
        //     $kesimpulan['min_zona'] = umur_geologi::where('id_umur', '=', min($max_umur_spesies))->get()->first()['zona_geo'];
        //     $kesimpulan['max_zona'] = umur_geologi::where('id_umur', '=', max($min_umur_spesies))->get()->first()['zona_geo'];
        //     $kesimpulan['min_umur'] = umur_geologi::where('id_umur', '=', min($max_umur_spesies))->get()->first()['umur_geo'];
        //     $kesimpulan['max_umur'] = umur_geologi::where('id_umur', '=', max($min_umur_spesies))->get()->first()['umur_geo'];
        //     $kesimpulan['min_umur_kata_per_kata'] = explode(' ', $kesimpulan['min_umur']);
        //     $kesimpulan['max_umur_kata_per_kata'] = explode(' ', $kesimpulan['max_umur']);
        // }

        $sample_detail = [
            'sample' => $sample,
            'studi_area' => $studi_area,
            'observer' => $observer,
            'sample_spesies' => $sample_spesies,
            'spesies_nanofosil' => $spesies_nanofosil,
            'i' => 0,
            'j' => 0,
            'umur_condition' => $umur_condition,
            'kesimpulan' => $kesimpulan,
            'umur_spesies' => $umur_spesies,
            'umur_awal_spesies' => $umur_awal_spesies,
        ];

        return response()->json($sample_detail, 200);
    }
}