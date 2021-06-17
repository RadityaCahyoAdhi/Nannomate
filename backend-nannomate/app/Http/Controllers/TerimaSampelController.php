<?php

namespace App\Http\Controllers;
use App\Models\sample;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class TerimaSampelController extends Controller
{
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if ($user['role'] != 'admin') {
            return response()->json(['error'=>'Unauthorised'], 401);
        } else {
            $sample = sample::find($id);
            if (is_null($sample)){
                return response()->json(['error'=>'Data Not Found!'], 404);
            }
            sample::where('id_sample', '=', $id)->update([
                'status' => 'diterima',
                'tanggal_diterima' => Carbon::now()->toDateString()
            ]);

            return response()->json(['success' => 'Data successfully updated'], 200);
        }
    }
}
