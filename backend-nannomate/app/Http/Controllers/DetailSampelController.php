<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Models\observer;
use App\Models\studi_area;
use App\Models\sample;
use App\Models\sample_spesies;
use App\Models\spesies_nanofosil;
use App\Models\zona_geologi;
use App\Models\umur_geologi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DetailSampelController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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

            //memastikan spesies sampel telah ditentukan
            if ($request->id_spesies == null && $request->spesies_tambahan == null) {
                return response()->json(['error'=> 'Spesies belum dimasukkan'], 400);
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

            //memastikan elemen-elemen id_spesies terdaftar dalam database
            if ($request->id_spesies != null) {
                foreach ($id_spesies as $id_spesies_value) {
                    if (is_null(spesies_nanofosil::find($id_spesies_value))){
                        $error = array('id_spesies' => ['Data Not Found!']);
                        return response()->json(['error'=> $error], 404);
                    }
                }
            }

            //memasukkan data ke dalam table observer
            $observer = observer::create([
                'nama_observer' => $request->nama_observer,
                'tanggal_penelitian' => $request->tanggal_penelitian
            ]);

            //memasukkan data ke dalam table studi_area
            $studi_area = studi_area::create([
                'id_observer' => $observer['id_observer'],
                'lokasi' => $request->lokasi,
                'litologi' => $request->litologi,
                'formasi' => $request->formasi,
                'longitude' => $request->longitude,
                'latitude' => $request->latitude
            ]);

            //memasukkan data ke dalam table sample
            $sample = sample::create([
                'id_studi_area' => $studi_area['id_studi_area'],
                'id_user' => $user['id_user'],
                'kode_sample' => $request->kode_sample,
                'kelimpahan' => $request->kelimpahan,
                'preparasi' => $request->preparasi,
                'pengawetan' => $request->pengawetan,
                'tujuan' => $request->tujuan,
                'stopsite' => $request->stopsite,
                'status' => 'menunggu verifikasi',
                'alasan' => '',
                'tanggal_dikirim' => Carbon::now()->toDateString(),
            ]);

            //memasukkan data spesies terdaftar yang terpilih ke dalam table sample_spesies
            if ($request->id_spesies != null) {
                $i = 0;
                foreach ($id_spesies as $id_spesies_value) {
                    sample_spesies::create([
                        'id_sample' => $sample['id_sample'],
                        'id_spesies' => $id_spesies_value,
                        'jumlah' => trim($id_spesies_jumlah[$i])
                    ]);
                    $i++;
                }
            }

            //memasukkan data spesies tambahan ke dalam table spesies_nanofosil dan sample_spesies
            if ($request->spesies_tambahan != null) {
                $j = 0;
                foreach ($spesies_nanofosil as $spesies_nanofosil_value) {
                    $id_spesies_tambahan = spesies_nanofosil::create([
                        'nama_spesies' => trim($spesies_nanofosil_value),
                        'status' => 'tambahan'
                    ]);
                    sample_spesies::create([
                        'id_sample' => $sample['id_sample'],
                        'id_spesies' => $id_spesies_tambahan['id_spesies'],
                        'jumlah' => trim($spesies_tambahan_jumlah[$j])
                    ]);
                    $j++;
                }
            }

            return response()->json(['success'=> 'data successfully created'], 200);
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
        $sample = sample::find($id);
        if (is_null($sample)){
            return response()->json(['error'=>'Data Not Found!'], 404);
        }
        $studi_area = studi_area::where('id_studi_area', '=', $sample['id_studi_area'])->get()->first();
        $observer = observer::where('id_observer', '=', $studi_area['id_observer'])->get()->first();
        $sample_spesies = sample_spesies::where('id_sample', '=', $sample['id_sample'])->get();

        // get setiap spesies_nanofosil sampel and zona_geologi dari spesies-spesies tersebut
        $i = 0;
        $spesies = [];
        $zona_geologi = [];
        foreach ($sample_spesies as $sample_spesiesValue) {
            $spesies[$i] = spesies_nanofosil::where('id_spesies', '=', $sample_spesiesValue->id_spesies)->get()[0];
            $zona_geologi[$i] = zona_geologi::where('id_spesies', '=', $sample_spesiesValue->id_spesies)->get();
            $i++;
        }

        //menghitung jumlah spesies berumur
        $m = 0;
        foreach ($zona_geologi as $zona_geologiValue) {
            if (sizeof($zona_geologiValue) != 0) {
                $m++;
            }
        }

        if ($m != 0) {
            //mengumpulkan umur awal spesies
            for ($k=0; $k<$m; $k++) {
                $umur_awal_spesies[$k] = $zona_geologi[$k][0]->id_umur;
            }

            $max_umur_awal_spesies = max($umur_awal_spesies);

            //mengumpulkan umur akhir spesies yang memiliki overlap dengan spesies termuda
            for ($l=0; $l<$m; $l++) {
                if ($zona_geologi[$l][count($zona_geologi[$l]) - 1]->id_umur >= $max_umur_awal_spesies) {
                    $umur_akhir_spesies[$l] = $zona_geologi[$l][count($zona_geologi[$l]) - 1]->id_umur;
                }
            }

            $min_umur_akhir_spesies = min($umur_akhir_spesies);
        }

        //define kesimpulan
        $kesimpulan = array();
        $kesimpulan['min_id_umur'] = null;
        $kesimpulan['max_id_umur'] = null;
        $kesimpulan['min_zona'] = null;
        $kesimpulan['max_zona'] = null;
        $kesimpulan['min_umur'] = null;
        $kesimpulan['max_umur'] = null;
        $kesimpulan['min_umur_kata_per_kata'] = null;
        $kesimpulan['max_umur_kata_per_kata'] = null;

        if ($m != 0) {
            //input kesimpulan
            $kesimpulan['min_id_umur'] = $max_umur_awal_spesies;
            $kesimpulan['max_id_umur'] = $min_umur_akhir_spesies;
            $kesimpulan['min_zona'] = umur_geologi::where('id_umur', '=', $max_umur_awal_spesies)->get()->first()['zona_geo'];
            $kesimpulan['max_zona'] = umur_geologi::where('id_umur', '=', $min_umur_akhir_spesies)->get()->first()['zona_geo'];
            $kesimpulan['min_umur'] = umur_geologi::where('id_umur', '=', $max_umur_awal_spesies)->get()->first()['umur_geo'];
            $kesimpulan['max_umur'] = umur_geologi::where('id_umur', '=', $min_umur_akhir_spesies)->get()->first()['umur_geo'];
            $kesimpulan['min_umur_kata_per_kata'] = explode(' ', $kesimpulan['min_umur']);
            $kesimpulan['max_umur_kata_per_kata'] = explode(' ', $kesimpulan['max_umur']);
        }

        $detail_sample = [
            'observer' => $observer,
            'studi_area' => $studi_area,
            'sample' => $sample,
            'sample_spesies' => $sample_spesies,
            'spesies' => $spesies,
            'zona_geologi' => $zona_geologi,
            'kesimpulan' => $kesimpulan
        ];

        return response()->json($detail_sample, 200);
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
        $sample = sample::find($id);
        if (is_null($sample)){
            return response()->json(['error'=>'Data Not Found!'], 404);
        }
        if ($user['id_user'] != $sample['id_user']) {
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

            //memastikan spesies sampel telah ditentukan
            if ($request->id_spesies == null && $request->spesies_tambahan == null) {
                return response()->json(['error'=> 'Spesies belum dimasukkan'], 400);
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

            //memastikan elemen-elemen id_spesies terdaftar dalam database
            if ($request->id_spesies != null) {
                foreach ($id_spesies as $id_spesies_value) {
                    if (is_null(spesies_nanofosil::find($id_spesies_value))){
                        $error = array('id_spesies' => ['Data Not Found!']);
                        return response()->json(['error'=> $error], 404);
                    }
                }
            }

            $sample = sample::find($id);
            $studi_area = studi_area::where('id_studi_area', '=', $sample['id_studi_area'])->first();
            $observer = observer::where('id_observer', '=', $studi_area['id_observer']);
            $sample_spesies = sample_spesies::where('id_sample', '=', $sample['id_sample'])->get();

            //hapus data sample_spesies lama & spesies 'tambahan' lama
            foreach ($sample_spesies as $sample_spesies_value) {
                $id_sample_spesies_value = $sample_spesies_value['id_spesies'];
                $sample_spesies_value->delete();
                spesies_nanofosil::where('id_spesies', '=', $id_sample_spesies_value)->where('status', '=', 'tambahan')->delete();
            }

            //memasukkan data spesies terdaftar yang terpilih ke dalam table sample_spesies
            if ($request->id_spesies != null) {
                $i = 0;
                foreach ($id_spesies as $id_spesies_value) {
                    sample_spesies::create([
                        'id_sample' => $sample['id_sample'],
                        'id_spesies' => $id_spesies_value,
                        'jumlah' => trim($id_spesies_jumlah[$i])
                    ]);
                    $i++;
                }
            }

            //memasukkan data spesies tambahan ke dalam table spesies_nanofosil dan sample_spesies
            if ($request->spesies_tambahan != null) {
                $j = 0;
                foreach ($spesies_nanofosil as $spesies_nanofosil_value) {
                    $id_spesies_tambahan = spesies_nanofosil::create([
                        'nama_spesies' => trim($spesies_nanofosil_value),
                        'status' => 'tambahan'
                    ]);
                    sample_spesies::create([
                        'id_sample' => $sample['id_sample'],
                        'id_spesies' => $id_spesies_tambahan['id_spesies'],
                        'jumlah' => trim($spesies_tambahan_jumlah[$j])
                    ]);
                    $j++;
                }
            }

            //memperbarui data pada table sample
            $sample->update([
                'kode_sample' => $request->kode_sample,
                'kelimpahan' => $request->kelimpahan,
                'preparasi' => $request->preparasi,
                'pengawetan' => $request->pengawetan,
                'tujuan' => $request->tujuan,
                'stopsite' => $request->stopsite,
                'status' => 'menunggu verifikasi',
                'alasan' => '',
                'tanggal_dikirim' => Carbon::now()->toDateString(),
            ]);

            //memperbarui data pada table studi_area
            $studi_area->update([
                'lokasi' => $request->lokasi,
                'litologi' => $request->litologi,
                'formasi' => $request->formasi,
                'longitude' => $request->longitude,
                'latitude' => $request->latitude
            ]);

            //memperbarui data pada table studi_area
            $observer->update([
                'nama_observer' => $request->nama_observer,
                'tanggal_penelitian' => $request->tanggal_penelitian
            ]);

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
        $sample = sample::find($id);
        if (is_null($sample)){
            return response()->json(['error'=>'Data Not Found!'], 404);
        }
        else {
            if ($user['id_user'] != $sample['id_user'] && $user['role'] != 'admin') {
                return response()->json(['error'=>'Unauthorised'], 403);
            } else {
                $studi_area = studi_area::where('id_studi_area', '=', $sample['id_studi_area'])->first();
                $observer = observer::where('id_observer', '=', $studi_area['id_observer']);
                $sample_spesies = sample_spesies::where('id_sample', '=', $sample['id_sample']);
                $sample_spesies_temp = $sample_spesies->get();

                $sample_spesies->delete();
                foreach ($sample_spesies_temp as $sample_spesies_temp_value) {
                    $spesies_tambahan = spesies_nanofosil::where('id_spesies', '=', $sample_spesies_temp_value['id_spesies'])->where('status', '=', 'tambahan');
                    $spesies_tambahan->delete();
                }
                $sample->delete();
                $studi_area->delete();
                $observer->delete();

                return response()->json(['success'=>'Delete Data Detail Sampel Success!'], 200);
            }
        }
    }

    public function detailSampelPreview(Request $request)
    {
        $user = Auth::user();
        if ($user['role'] != 'user login') {
            return response()->json(['error'=>'Unauthorised'], 403);
        } else {
            $validator = Validator::make($request->all(), [
                'id_spesies' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 400);
            }

            //memastikan masukan spesies yang telah terdaftar dalam database telah sesuai
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

            //get setiap spesies_nanofosil terdaftar sampel and zona_geologi dari spesies-spesies tersebut
            $i = 0;
            $spesies = [];
            $zona_geologi = [];
            foreach ($id_spesies as $id_spesies_value) {
                $spesies[$i] = spesies_nanofosil::where('id_spesies', '=', $id_spesies_value)->get()[0]->toArray();
                $zona_geologi[$i] = zona_geologi::where('id_spesies', '=', $id_spesies_value)->get()->toArray();
                $i++;
            }

            //memastikan masukan spesies tambahan beserta umur awal dan akhirnya telah sesuai
            if($request->spesies_tambahan != null) {
                $spesies_tambahan = explode(', ', $request->spesies_tambahan);
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
                    if(count($spesies_tambahan) != count($umur_awal_tambahan)) {
                        return response()->json(['error'=> 'Ada umur awal spesies tambahan yang belum dimasukkan'], 400);
                    }
                }
                else {
                    return response()->json(['error'=> 'Ada umur awal spesies tambahan yang belum dimasukkan'], 400);
                }

                //memastikan umur akhir spesies tambahan telah dimasukkan
                if($request->umur_akhir_tambahan != null) {
                    if(count($spesies_tambahan) != count($umur_akhir_tambahan)) {
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

            if($request->spesies_tambahan != null) {
                //memasukkan nama spesies tambahan
                for ($i=0; $i < count($spesies_tambahan); $i++) {
                    $index = count($spesies);
                    $spesies[$index]['nama_spesies'] = trim($spesies_tambahan[$i]);
                    $spesies[$index]['status'] = 'tambahan';
                }

                //memasukkan zona geologi spesies tambahan
                if($request->umur_awal_tambahan != null && $request->umur_akhir_tambahan != null) {
                    for ($i=0; $i < count($umur_awal_tambahan); $i++) {
                        $zona_geologi_tambahan = [];
                        for($j=0; $j <= ($umur_akhir_tambahan[$i] - $umur_awal_tambahan[$i]); $j++) {
                            $zona_geologi_tambahan[$j]['id_umur'] = ($umur_awal_tambahan[$i] + $j);
                        }
                        $zona_geologi[count($zona_geologi)] = $zona_geologi_tambahan;
                    }
                }
            }

            //menghitung jumlah spesies berumur
            $m = 0;
            foreach ($zona_geologi as $zona_geologiValue) {
                if (sizeof($zona_geologiValue) != 0) {
                    $m++;
                }
            }

            if ($m != 0) {
                //mengumpulkan umur awal spesies
                for ($k=0; $k<$m; $k++) {
                    $umur_awal_spesies[$k] = $zona_geologi[$k][0]['id_umur'];
                }

                $max_umur_awal_spesies = max($umur_awal_spesies);

                //mengumpulkan umur akhir spesies yang memiliki overlap dengan spesies termuda
                for ($l=0; $l<$m; $l++) {
                    if ($zona_geologi[$l][count($zona_geologi[$l]) - 1]['id_umur'] >= $max_umur_awal_spesies) {
                        $umur_akhir_spesies[$l] = $zona_geologi[$l][count($zona_geologi[$l]) - 1]['id_umur'];
                    }
                }

                $min_umur_akhir_spesies = min($umur_akhir_spesies);
            }

            //define kesimpulan
            $kesimpulan = array();
            $kesimpulan['min_id_umur'] = null;
            $kesimpulan['max_id_umur'] = null;
            $kesimpulan['min_zona'] = null;
            $kesimpulan['max_zona'] = null;
            $kesimpulan['min_umur'] = null;
            $kesimpulan['max_umur'] = null;
            $kesimpulan['min_umur_kata_per_kata'] = null;
            $kesimpulan['max_umur_kata_per_kata'] = null;

            if ($m != 0) {
                //input kesimpulan
                $kesimpulan['min_id_umur'] = $max_umur_awal_spesies;
                $kesimpulan['max_id_umur'] = $min_umur_akhir_spesies;
                $kesimpulan['min_zona'] = umur_geologi::where('id_umur', '=', $max_umur_awal_spesies)->get()->first()['zona_geo'];
                $kesimpulan['max_zona'] = umur_geologi::where('id_umur', '=', $min_umur_akhir_spesies)->get()->first()['zona_geo'];
                $kesimpulan['min_umur'] = umur_geologi::where('id_umur', '=', $max_umur_awal_spesies)->get()->first()['umur_geo'];
                $kesimpulan['max_umur'] = umur_geologi::where('id_umur', '=', $min_umur_akhir_spesies)->get()->first()['umur_geo'];
                $kesimpulan['min_umur_kata_per_kata'] = explode(' ', $kesimpulan['min_umur']);
                $kesimpulan['max_umur_kata_per_kata'] = explode(' ', $kesimpulan['max_umur']);
            }

            $detail_sample_preview = [
                'spesies' => $spesies,
                'zona_geologi' => $zona_geologi,
                'kesimpulan' => $kesimpulan
            ];

            return response()->json($detail_sample_preview, 200);
        }
    }
}
