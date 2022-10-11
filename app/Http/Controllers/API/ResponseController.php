<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResponseController extends Controller
{
    public function sendSuccess($data, $message, $code)
    {
        $response = [
            'success' => true,
            'code' => $code,
            'message' => $message,
            'data' => $data
        ];

        return response()->json($response,$code);
    }

    public function sendError($message, $code)
    {
        $response = [
            'success' => false,
            'code' => $code,
            'message' => $message,
            'data' => null,
        ];

        return response()->json($response,$code);
    }
}
