<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\sample;

class CountSampelController extends Controller
{
    //hitung jumlah sampel menunggu verifikasi
    public function countMenungguVerifikasi()
    {
        $user = Auth::user();
        if ($user['role'] != 'admin') {
            return response()->json(['error'=>'Unauthorised'], 401);
        } else {
            $jumlah_menunggu_verifikasi = sample::where('status', '=', 'menunggu verifikasi')->count();

            return response()->json(['count_menunggu_verifikasi'=> $jumlah_menunggu_verifikasi], 200);
        }
    }

    //hitung jumlah sampel diterima
    public function countDiterima()
    {
        $user = Auth::user();
        if ($user['role'] != 'admin') {
            return response()->json(['error'=>'Unauthorised'], 401);
        } else {
            $jumlah_diterima = sample::where('status', '=', 'diterima')->count();

            return response()->json(['count_diterima'=> $jumlah_diterima], 200);
        }
    }

    //hitung jumlah sampel menunggu verifikasi
    public function countDitolak()
    {
        $user = Auth::user();
        if ($user['role'] != 'admin') {
            return response()->json(['error'=>'Unauthorised'], 401);
        } else {
            $jumlah_ditolak = sample::where('status', '=', 'ditolak')->count();

            return response()->json(['count_ditolak'=> $jumlah_ditolak], 200);
        }
    }
}
