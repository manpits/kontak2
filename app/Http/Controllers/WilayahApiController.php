<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Desa;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use Illuminate\Http\Request;

class WilayahApiController extends Controller
{
    public function getAllRegionData(Request $request){
        $provs = Provinsi::all();
        $kabs = Kabupaten::all();
        $kecs = Kecamatan::all();
        $desas = Desa::all();

        //
        return response()->json([
            'code'     => 200,
            'token-expired'    => Carbon::now()->diffInSeconds(date("Y-m-d H:i:s", json_decode(base64_decode(explode('.', substr($request->header('Authorization'), 7))[1]))->exp)),
            'status' => 'success', 
            'provinsi' => $provs,
            'kabupaten' => $kabs,
            'kecamatan' => $kecs,
            'desa' => $desas,
        ],200); 

    }

    public function getProvinsi(Request $request){
        $provs = Provinsi::where('id',$request->prov_id)->get(['id','provinsi AS value']);
        return response()->json([
            'code'     => 200,
            'token-expired'    => Carbon::now()->diffInSeconds(date("Y-m-d H:i:s", json_decode(base64_decode(explode('.', substr($request->header('Authorization'), 7))[1]))->exp)),
            'status' => 'success', 
            'provinsi' => $provs,
        ],200); 
    }

    public function getKabupatan(Request $request){
        $kabs = Kabupaten::where('prov_id',$request->prov_id)->get(['id','kabupaten AS value']);
        return response()->json([
            'code'     => 200,
            'token-expired'    => Carbon::now()->diffInSeconds(date("Y-m-d H:i:s", json_decode(base64_decode(explode('.', substr($request->header('Authorization'), 7))[1]))->exp)),
            'status' => 'success', 
            'kabupaten' => $kabs,
        ],200); 
    }

    public function getKecamatan(Request $request){
        $kecs = Kecamatan::where('kab_id',$request->kab_id)->get(['id','kecamatan AS value']);
        return response()->json([
            'code'     => 200,
            'token-expired'    => Carbon::now()->diffInSeconds(date("Y-m-d H:i:s", json_decode(base64_decode(explode('.', substr($request->header('Authorization'), 7))[1]))->exp)),
            'status' => 'success', 
            'kecamatan' => $kecs,
        ],200); 
    }

    public function getDesa(Request $request){
        $desas = Desa::where('kec_id',$request->kec_id)->get(['id','desa AS value']);
        return response()->json([
            'code'     => 200,
            'token-expired'    => Carbon::now()->diffInSeconds(date("Y-m-d H:i:s", json_decode(base64_decode(explode('.', substr($request->header('Authorization'), 7))[1]))->exp)),
            'status' => 'success', 
            'desa' => $desas,
        ],200); 
    }
}
