<?php

namespace App\Helpers;

class ApiResponse
{
    public static function success($data = null, ?string $message = null, int $code = 200)
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => $message,
        ], $code);
    }

    public static function error(string $message, $errors = null, int $code = 400)
    {
        return response()->json([
            'success' => false,
            'data' => null,
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }
}
