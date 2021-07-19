<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\observer;

class DaftarDataDitolakController extends Controller
{
    //ambil daftar data ditolak bagi admin
    public function bagiAdmin()
    {
        $user = Auth::user();
        if ($user['role'] != 'admin') {
            return response()->json(['error'=>'Unauthorised'], 403);
        } else {
            $daftarDataDiterima = observer::join('studi_area','studi_area.id_observer', '=', 'observer.id_observer')
            ->join('sample', 'sample.id_studi_area', '=', 'studi_area.id_studi_area')
            ->where('status', '=', 'ditolak')
            ->get(['sample.id_sample', 'observer.nama_observer', 'studi_area.lokasi', 'studi_area.formasi', 'sample.kode_sample', 'sample.alasan']);

            return response()->json($daftarDataDiterima, 200);
        }
    }

    //ambil daftar data ditolak bagi user
    public function bagiUser()
    {
        $user = Auth::user();
        if ($user['role'] != 'user login') {
            return response()->json(['error'=>'Unauthorised'], 403);
        } else {
            $daftarDataDiterima = observer::join('studi_area','studi_area.id_observer', '=', 'observer.id_observer')
            ->join('sample', 'sample.id_studi_area', '=', 'studi_area.id_studi_area')
            ->where('id_user', '=', $user['id_user'])
            ->where('status', '=', 'ditolak')
            ->get(['sample.id_sample', 'observer.nama_observer', 'studi_area.lokasi', 'studi_area.formasi', 'sample.kode_sample', 'sample.alasan']);

            return response()->json($daftarDataDiterima, 200);
        }
    }
}
