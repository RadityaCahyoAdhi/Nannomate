<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\sample;

class TolakSampelController extends Controller
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

            $validator = Validator::make($request->all(), [
                'alasan' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401);
            }

            sample::where('id_sample', '=', $id)->update([
                'status' => 'ditolak',
                'alasan' => $request->alasan
            ]);

            return response()->json(['success' => 'Data successfully updated'], 200);
        }
    }
}
