<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use Illuminate\Http\Request;

class WilayahApiController extends Controller
{
    public function getProvinsi(Request $request){
        return Provinsi::select(['id','provinsi AS value'])->get();
    }

    public function getKabupatan(Request $request){
        $kabs = Kabupaten::where('prov_id',$request->prov_id)->get(['id','kabupaten AS value']);
        return $kabs;
    }

    public function getKecamatan(Request $request){
        $kecs = Kecamatan::where('kab_id',$request->kab_id)->get(['id','kecamatan AS value']);
        return $kecs;
    }

    public function getDesa(Request $request){
        $desas = Desa::where('kec_id',$request->kec_id)->get(['id','desa AS value']);
        return $desas;
    }
}
