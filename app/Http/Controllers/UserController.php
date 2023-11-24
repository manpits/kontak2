<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required'
        ]);

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
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'meta' => [
                        'code' => 402,
                        'message' => 'Invalid Credentials',
                    ],
                ], 402);
            }
        } catch (JWTException $e) {
            return response()->json([
                'meta' => [
                    'code' => 403,
                    'message' => 'Could not create token',
                ],
            ], 403);
        }
        return response()->json([
            'meta' => [
                'code' => 200,
                'message' => 'Successfully logged in',
                'token' => $token
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
