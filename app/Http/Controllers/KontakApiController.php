<?php

namespace App\Http\Controllers;

use JWTAuth;
use Carbon\Carbon;
use App\Models\Desa;
use App\Models\Kontak;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use GuzzleHttp\Psr7\Response;
use App\Http\Controllers\UserController;

class KontakApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        return response()->json([
            'code'     => 200,
            'user'     => $auth('api')->user()->makeHidden('email_verified_at','created_at','updated_at'),
            'token-expired'    => Carbon::now()->diffInSeconds(date("Y-m-d H:i:s", json_decode(base64_decode(explode('.', substr($request->header('Authorization'), 7))[1]))->exp)),
            'status' => 'success', 
            'kontak' => Kontak::select(['id','nama','alamat','desa_id','telepon','lahir','gender'])->get(),
        ], 200); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $kontak = new Kontak();
        $kontak->fill($request->all());
        $kontak->save();
        return response()->json([
            'code'     => 200,
            'token-expired'    => Carbon::now()->diffInSeconds(date("Y-m-d H:i:s", json_decode(base64_decode(explode('.', substr($request->header('Authorization'), 7))[1]))->exp)),
            'status' => 'success', 
            'kontak' => $kontak,
        ],200); 
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        return response()->json([
            'code'     => 200,
            'token-expired'    => Carbon::now()->diffInSeconds(date("Y-m-d H:i:s", json_decode(base64_decode(explode('.', substr($request->header('Authorization'), 7))[1]))->exp)),
            'status' => 'success', 
            'kontak' => Kontak::find($id,['id','nama','alamat','desa_id','telepon','lahir','gender']),
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $kontak = Kontak::find($id);
        $kontak->fill($request->all())->save();
        return response()->json([
            'code'     => 200,
            'token-expired'    => Carbon::now()->diffInSeconds(date("Y-m-d H:i:s", json_decode(base64_decode(explode('.', substr($request->header('Authorization'), 7))[1]))->exp)),
            'status' => 'success', 
            'kontak' => $kontak,
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $kontak = Kontak::find($id)->delete();
        return response()->json([
            'code'     => 200,
            'token-expired'    => Carbon::now()->diffInSeconds(date("Y-m-d H:i:s", json_decode(base64_decode(explode('.', substr($request->header('Authorization'), 7))[1]))->exp)),
            'status' => 'success',            
        ],200);
    }
}
