<?php

namespace App\Exports;

// use App\Models\observer;
// use App\Models\studi_area;
// use App\Models\sample;
// use App\Models\sample_spesies;
use App\Models\spesies_nanofosil;
use App\Models\zona_geologi;
use App\Models\umur_geologi;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SampleExportByRequest implements FromView, WithStyles, WithColumnWidths
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct(string $nama_observer, string $tanggal_penelitian, string $lokasi, string $litologi, string $formasi, string $longitude, string $latitude, string $kode_sample, string $kelimpahan, string $preparasi, string $pengawetan, string $tujuan, string $stopsite, string $id_spesies = null, string $spesies_tambahan = null, string $id_spesies_jumlah = null, string $spesies_tambahan_jumlah = null)
    {
        $this->nama_observer = $nama_observer;
        $this->tanggal_penelitian = $tanggal_penelitian;
        $this->lokasi = $lokasi;
        $this->litologi = $litologi;
        $this->formasi = $formasi;
        $this->longitude = $longitude;
        $this->latitude = $latitude;
        $this->kode_sample = $kode_sample;
        $this->kelimpahan = $kelimpahan;
        $this->preparasi = $preparasi;
        $this->pengawetan = $pengawetan;
        $this->tujuan = $tujuan;
        $this->stopsite = $stopsite;
        $this->id_spesies = $id_spesies;
        $this->spesies_tambahan = $spesies_tambahan;
        $this->id_spesies_jumlah = $id_spesies_jumlah;
        $this->spesies_tambahan_jumlah = $spesies_tambahan_jumlah;
    }
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A14')->getAlignment()->setTextRotation(-90);
        $sheet->getStyle('G16:AZ16')->getAlignment()->setTextRotation(-90);
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A2_PAPER);
    }

    public function columnWidths(): array
    {
        return [
            'G' => 4.335, //2.89 == 2.11(excel)
            'H' => 4.335,
            'I' => 4.335,
            'J' => 4.335,
            'K' => 4.335,
            'L' => 4.335,
            'M' => 4.335,
            'N' => 4.335,
            'O' => 4.335,
            'P' => 4.335,
            'Q' => 4.335,
            'R' => 4.335,
            'S' => 4.335,
            'T' => 4.335,
            'U' => 4.335,
            'V' => 4.335,
            'W' => 4.335,
            'X' => 4.335,
            'Y' => 4.335,
            'Z' => 4.335,
            'AA' => 4.335,
            'AB' => 4.335,
            'AC' => 4.335,
            'AD' => 4.335,
            'AE' => 4.335,
            'AF' => 4.335,
            'AG' => 4.335,
            'AH' => 4.335,
            'AI' => 4.335,
            'AJ' => 4.335,
            'AK' => 4.335,
            'AL' => 4.335,
            'AM' => 4.335,
            'AN' => 4.335,
            'AO' => 4.335,
            'AP' => 4.335,
            'AQ' => 4.335,
            'AR' => 4.335,
            'AS' => 4.335,
            'AT' => 4.335,
            'AU' => 4.335,
            'AV' => 4.335,
            'AW' => 4.335,
            'AX' => 4.335,
            'AY' => 4.335,
            'AZ' => 4.335
        ];
    }

    public function view(): View
    {
        $observer['nama_observer'] = $this->nama_observer;
        $observer['tanggal_penelitian'] = $this->tanggal_penelitian;
        $studi_area['lokasi'] = $this->lokasi;
        $studi_area['litologi'] = $this->litologi;
        $studi_area['formasi'] = $this->formasi;
        $studi_area['longitude'] = $this->longitude;
        $studi_area['latitude'] = $this->latitude;
        $sample['kode_sample'] = $this->kode_sample;
        $sample['kelimpahan'] = $this->kelimpahan;
        $sample['preparasi'] = $this->preparasi;
        $sample['pengawetan'] = $this->pengawetan;
        $sample['tujuan'] = $this->tujuan;
        $sample['stopsite'] = $this->stopsite;
        $sample_spesies = null;
        $i = 0;

        if($this->id_spesies != null) {
            $id_spesies = explode(', ', $this->id_spesies);
            if($this->id_spesies_jumlah != null) {
                $id_spesies_jumlah = explode(', ', $this->id_spesies_jumlah);
                foreach ($id_spesies_jumlah as $id_spesies_jumlah_value) {
                    $sample_spesies[$i]['jumlah'] = $id_spesies_jumlah_value;
                    $i++;
                }
            }
        }

        if($this->spesies_tambahan != null) {
            $spesies_nanofosil = explode(', ', $this->spesies_tambahan);
            if($this->spesies_tambahan_jumlah != null) {
                $spesies_tambahan_jumlah = explode(', ', $this->spesies_tambahan_jumlah);
                foreach ($spesies_tambahan_jumlah as $spesies_tambahan_jumlah_value) {
                    $sample_spesies[$i]['jumlah'] = $spesies_tambahan_jumlah_value;
                    $i++;
                }
            }
        }

        $spesies_nanofosil = Array();
        if ($this->id_spesies != null) {
            $id_spesies = explode(', ', $this->id_spesies);
            foreach ($id_spesies as $id_spesies_value) {
                $spesies_nanofosil[] = spesies_nanofosil::where('id_spesies', '=', $id_spesies_value)->get();
            }
        }
        if ($this->spesies_tambahan != null) {
            $spesies_tambahan = explode(', ', $this->spesies_tambahan);
            foreach ($spesies_tambahan as $spesies_tambahan_value) {
                $spesies_nanofosil[] = [[
                    'id_spesies' => null,
                    'nama_spesies' => $spesies_tambahan_value,
                    'status' => 'tambahan'
                ]];
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
        $umur_spesies = Array();
        $i = 0;
        foreach ($spesies_nanofosil as $spesies_nanofosil_value) {
            $spesies_nanofosil_value[0]['zona'] = zona_geologi::where('id_spesies', '=', $spesies_nanofosil_value[0]['id_spesies'])->get();
            // if ($spesies_nanofosil_value[0]['zona']->count() != 0) {
            //     $count_spesies_berumur++;
            // }
            $j = 0;
            foreach ($spesies_nanofosil_value[0]['zona'] as $zona_value) {
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

        $m = count($umur_spesies); //menghitung jumlah spesies berumur
        if ($m != 0) {
            //mengumpulkan umur awal spesies
            for ($k=0; $k<$m; $k++) {
                $umur_awal_spesies[$k] = $umur_spesies[$k][0];
            }

            $max_umur_awal_spesies = max($umur_awal_spesies);

            //mengumpulkan umur akhir spesies yang memiliki overlap dengan spesies termuda
            for ($l=0; $l<$m; $l++) {
                if ($umur_spesies[$l][count($umur_spesies[$l]) - 1] >= $max_umur_awal_spesies) {
                    $umur_akhir_spesies[$l] = $umur_spesies[$l][count($umur_spesies[$l]) - 1];
                }
            }

            $min_umur_akhir_spesies = min($umur_akhir_spesies);
        }

        // $max_count_umur = max($count_umur);

        //define kesimpulan
        $kesimpulan = array();
        $kesimpulan['min_zona'] = null;
        $kesimpulan['max_zona'] = null;
        $kesimpulan['min_umur'] = null;
        $kesimpulan['max_umur'] = null;
        $kesimpulan['min_umur_kata_per_kata'] = null;
        $kesimpulan['max_umur_kata_per_kata'] = null;

        if ($m != 0) {
            //input kesimpulan
            $kesimpulan['min_zona'] = umur_geologi::where('id_umur', '=', $max_umur_awal_spesies)->get()->first()['zona_geo'];
            $kesimpulan['max_zona'] = umur_geologi::where('id_umur', '=', $min_umur_akhir_spesies)->get()->first()['zona_geo'];
            $kesimpulan['min_umur'] = umur_geologi::where('id_umur', '=', $max_umur_awal_spesies)->get()->first()['umur_geo'];
            $kesimpulan['max_umur'] = umur_geologi::where('id_umur', '=', $min_umur_akhir_spesies)->get()->first()['umur_geo'];
            $kesimpulan['min_umur_kata_per_kata'] = explode(' ', $kesimpulan['min_umur']);
            $kesimpulan['max_umur_kata_per_kata'] = explode(' ', $kesimpulan['max_umur']);
        }

        //perlu revisi
        // if ($max_count_umur == $count_spesies_berumur && $count_spesies_berumur != 0) {
        //     $kesimpulan['rentang_zona'] = array_keys($count_umur, $max_count_umur);
        //     $kesimpulan['min_zona'] = $kesimpulan['rentang_zona'][0];
        //     $kesimpulan['max_zona'] = $kesimpulan['rentang_zona'][count($kesimpulan['rentang_zona']) - 1];
        //     $kesimpulan['min_umur'] = umur_geologi::where('zona_geo', '=', $kesimpulan['min_zona'])->get()->first()['umur_geo'];
        //     $kesimpulan['max_umur'] = umur_geologi::where('zona_geo', '=', $kesimpulan['max_zona'])->get()->first()['umur_geo'];
        //     $kesimpulan['min_umur_kata_per_kata'] = explode(' ', $kesimpulan['min_umur']);
        //     $kesimpulan['max_umur_kata_per_kata'] = explode(' ', $kesimpulan['max_umur']);
        // }
        // elseif ($max_count_umur != $count_spesies_berumur) {
        //     $j = 0;
        //     foreach ($spesies_nanofosil as $spesies_nanofosil_value) {
        //         $spesies_nanofosil_value[0]['zona'] = zona_geologi::where('id_spesies', '=', $spesies_nanofosil_value[0]['id_spesies'])->get();
        //         $k = 0;
        //         if ($spesies_nanofosil_value[0]['zona']->count() != 0) {
        //             foreach ($spesies_nanofosil_value[0]['zona'] as $zona_value) {
        //                 $rentang_umur_spesies[$j][$k] = $zona_value['id_umur'];
        //                 $k++;
        //             }
        //         $min_umur_spesies[$j] = $rentang_umur_spesies[$j][0];
        //         $max_umur_spesies[$j] = $rentang_umur_spesies[$j][count($rentang_umur_spesies[$j]) - 1];
        //         $j++;
        //         }
        //     }
        //     $kesimpulan['min_zona'] = umur_geologi::where('id_umur', '=', min($max_umur_spesies))->get()->first()['zona_geo'];
        //     $kesimpulan['max_zona'] = umur_geologi::where('id_umur', '=', max($min_umur_spesies))->get()->first()['zona_geo'];
        //     $kesimpulan['min_umur'] = umur_geologi::where('id_umur', '=', min($max_umur_spesies))->get()->first()['umur_geo'];
        //     $kesimpulan['max_umur'] = umur_geologi::where('id_umur', '=', max($min_umur_spesies))->get()->first()['umur_geo'];
        //     $kesimpulan['min_umur_kata_per_kata'] = explode(' ', $kesimpulan['min_umur']);
        //     $kesimpulan['max_umur_kata_per_kata'] = explode(' ', $kesimpulan['max_umur']);
        // }
        //perlu revisi

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
        ];

        return view('exports.sample', $sample_detail);
    }
}
