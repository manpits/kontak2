<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Response;
use Exception;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException; 

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    // public function register(): void
    // {
    //     $this->reportable(function (Throwable $e) {
    //         //
    //     });
    // }

    public function register()
    {
        $this->renderable(function(TokenInvalidException $e, $request){
                return Response::json([
                    'meta' => [
                        'code' => 401,
                        'status' => 'error',
                        'message' => 'Invalid token',
                    ],
                    'data' => [
                        'exceptions' => $e
                    ]
                ],401);
        });
        $this->renderable(function (TokenExpiredException $e, $request) {
            return Response::json([
                'meta' => [
                    'code' => 401,
                    'status' => 'error',
                    'message' => 'Token has Expired',
                ],
                'data' => [
                    'exceptions' => $e
                ]
            ],401);
        });

        $this->renderable(function (JWTException $e, $request) {
            return Response::json([
                'meta' => [
                    'code' => 401,
                    'status' => 'error',
                    'message' => 'Token not parsed',
                ],
                'data' => [
                    'exceptions' => $e
                ]
            ],401);
        });

        $this->renderable(function (Exception $e, $request) {
            if ($exception instanceof MethodNotAllowedHttpException){
                return Response::json([
                    'meta' => [
                        'code' => 401,
                        'status' => 'error',
                        'message' => 'Method not allowed ..',
                    ],
                    'data' => [
                        'exceptions' => $e
                    ]
                ],401);
            }
        });

        // $this->renderable(function (Throwable $e, $request) {
        //     return Response::json([
        //         'meta' => [
        //             'code' => 401,
        //             'status' => 'error',
        //             'message' => 'Something wrong ..',
        //         ],
        //         'data' => [
        //             'exceptions' => $e
        //         ]
        //     ],401);
        // });

    }
}
