<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

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
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {

        if ($exception instanceof UnauthorizedHttpException) {
            return response()->json([
                'code' => 401,
                'message' => $exception->getMessage(),
                'data' => null
            ], 401);
        }
        else if ($exception instanceof TokenExpiredException) {
            return response()->json([
                'code' => 401,
                'message' => 'Token has expired',
                'data' => null
            ], 401);

        } else if ($exception instanceof TokenInvalidException) {
            return response()->json([
                'code' => 401,
                'message' => 'Token is invalid',
                'data' => null
            ], 401);
        } else if ($exception instanceof TokenBlacklistedException) {
            return response()->json([
                'code' => 401,
                'message' => 'The token has been blacklisted',
                'data' => null
            ], 401);
        } else if ($exception instanceof JWTException) {
            return response()->json([
                'code' => 500,
                'message' => 'Error fetching the token',
                'data' => null
            ], 500);
        } else {
            return response()->json([
                'code' => 500,
                'message' => 'Server Error: ' . $exception->getMessage(),
                'data' => get_class($exception)
            ], 500);
        }
        // return parent::render($request, $exception);
    }
}
