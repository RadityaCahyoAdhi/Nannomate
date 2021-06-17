<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\observer;
use App\Models\studi_area;
use App\Models\sample;

class DaftarMenungguVerifikasiController extends Controller
{
    //ambil daftar data menunggu verifikasi bagi admin
    public function bagiAdmin()
    {
        $user = Auth::user();
        if ($user['role'] != 'admin') {
            return response()->json(['error'=>'Unauthorised'], 401);
        } else {
            $daftarMenungguVerifikasi = observer::join('studi_area','studi_area.id_observer', '=', 'observer.id_observer')
            ->join('sample', 'sample.id_studi_area', '=', 'studi_area.id_studi_area')
            ->where('status', '=', 'menunggu verifikasi')
            ->get(['sample.id_sample', 'observer.nama_observer', 'studi_area.lokasi', 'studi_area.formasi', 'sample.kode_sample', 'sample.tanggal_dikirim']);

            return response()->json($daftarMenungguVerifikasi, 200);
        }
    }

    //ambil daftar data menunggu verifikasi bagi user
    public function bagiUser()
    {
        $user = Auth::user();
        if ($user['role'] != 'user login') {
            return response()->json(['error'=>'Unauthorised'], 401);
        } else {
            $daftarMenungguVerifikasi = observer::join('studi_area','studi_area.id_observer', '=', 'observer.id_observer')
            ->join('sample', 'sample.id_studi_area', '=', 'studi_area.id_studi_area')
            ->where('id_user', '=', $user['id_user'])
            ->where('status', '=', 'menunggu verifikasi')
            ->get(['sample.id_sample', 'observer.nama_observer', 'studi_area.lokasi', 'studi_area.formasi', 'sample.kode_sample', 'sample.tanggal_dikirim']);

            return response()->json($daftarMenungguVerifikasi, 200);
        }
    }
}
