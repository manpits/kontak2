<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Response;
use Exception;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException; 
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
                        'code' => 402,
                        'message' => 'Token invalid',
                    ]
                ],402);
        });
        $this->renderable(function (TokenExpiredException $e, $request) {
            return Response::json([
                'meta' => [
                    'code' => 403,
                    'message' => 'Token has expired',
                ]
            ],403);
        });

        $this->renderable(function (JWTException $e, $request) {
            return Response::json([
                'meta' => [
                    'code' => 404,
                    'message' => 'Token not parsed',
                ]
            ],404);
        });

        $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            return Response::json([
                'meta' => [
                    'code' => 405,
                    'message' => 'HTTP method not allowed',
                ]
            ],405);
        });

        $this->renderable(function (NotFoundHttpException $e, $request) {
            return Response::json([
                'meta' => [
                    'code' => 406,
                    'message' => 'Route not found',
                ]
            ],406);
        });

    }
}
