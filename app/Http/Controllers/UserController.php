<?php

namespace App\Http\Controllers;

use JWTAuth;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use PhpParser\Node\Stmt\TryCatch;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    public function register(Request $request)
    {
        try {
            $this->validate($request, [
                'name'=>'required',
                'email'=>'required|email|unique:users',
                'password'=>'required'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'meta' => [
                    'code' => 200,
                    'status'=>'failed',
                    'message' => $e->getMessage(),
                ],
            ],200);
        }
        $user = new User([
            'name'=> $request->input('name'),
            'email'=> $request->input('email'),
            'password'=> bcrypt($request->input('password'))
        ]);

        $user->save();

        return response()->json([
            'meta' => [
                'code' => 200,
                'message' => 'Successfully create user',
                'data' => $user->select('name','email')
            ],
        ],200);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');
        try {
            // Config::set('jwt.ttl', env('JWT_TTL', 1));
            JWTAuth::factory()->setTTL(60*24);
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'meta' => [
                        'code' => 401,
                        'message' => 'Invalid Credentials',
                    ],
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json([
                'meta' => [
                    'code' => 500,
                    'message' => 'Could not create token',
                ],
            ], 500);
        }
        $exp_in_seconds = Carbon::now()->diffInSeconds(date("Y-m-d H:i:s", json_decode(base64_decode(explode('.', $token)[1]))->exp));
        return response()->json([
            'meta' => [
                'code' => 200,
                'message' => 'Successfully logged in',
                'token' => $token,
                'token-expired' => $exp_in_seconds,
            ],
        ], 200);
    }

    public function getUser(){
        $user = auth('api')->user();
        return response()->json([
            'meta' => [
                'code' => 200,
                'message' => 'Successfully get user',
            ],
            'data' => [
                'user'=>$user
            ],
        ], 200);
    }

    public function logout(Request $request){
        // get token
        $token = JWTAuth::getToken();

        // invalidate token
        $invalidate = JWTAuth::invalidate($token);
        
        if($invalidate) {
            return response()->json([
                'meta' => [
                    'code' => 200,
                    'message' => 'Successfully logged out',
                ],
                'data' => [],
            ],200);
        }
    }
}
