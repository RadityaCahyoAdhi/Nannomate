<?php

namespace App\Http\Controllers;
use App\Models\umur_geologi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UmurGeologiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if ($user['role'] != 'admin') {
            return response()->json(['error'=>'Unauthorised'], 403);
        } else {
            $daftarUmur = umur_geologi::all();

            return response()->json($daftarUmur, 200);
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
        $umur = umur_geologi::find($id);

        return response()->json($umur, 200);
    }
}
