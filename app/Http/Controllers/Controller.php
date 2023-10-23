<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected int $errorCode;
    protected string $errorMessage;

    public function responseError($code, $message, $data = null)
    {
        return response()->json([
            'code' => $code,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public function responseSuccess($data = null, $message = '')
    {
        return response()->json([
            'code' => 200,
            'message' => '',
            'data' => $data
        ], 200);
    }
}
