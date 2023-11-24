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
                'code' => 201,
                'status' => 'success',
                'message' => 'Successfully Created user',
            ],
        ],201);
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
                        'status' => 'error',
                        'message' => 'Invalid Credentials',
                    ],
                ], 402);
            }
        } catch (JWTException $e) {
            return response()->json([
                'meta' => [
                    'code' => 500,
                    'status' => 'error',
                    'message' => 'Could not create token',
                ],
            ], 500);
        }
        return response()->json([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Successfully logged in',
                'token' => $token
            ],
        ], 200);
    }

    public function getUser(){
        $user = auth('api')->user();
        return response()->json([
            'meta' => [
                'code' => 201,
                'status' => 'success',
                'message' => 'Successfully get user',
            ],
            'data' => [
                'user'=>$user
            ],
        ], 201);
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
                    'status' => 'success',
                    'message' => 'Successfully logged out',
                ],
                'data' => [],
            ]);
        }
    }
}
