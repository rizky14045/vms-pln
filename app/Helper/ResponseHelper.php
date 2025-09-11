<?php 

namespace App\Helper;

use Illuminate\Http\JsonResponse;

class ResponseHelper {
    
    public static function response($status,$message,$errors,$data,$code_status): JsonResponse{

        return response()->json([
            'status' => $status,
            'message' => $message, // pesan error
            'data' => $data, // data
            'errors' => $errors // pesan error message
        ], $code_status);

    }

    public static function successServiceResponse(
        int $code_status,
        bool $status,
        string $message = '',
        $data = null
    ): array {
        return [
            'code_status' => $code_status,
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ];
    }

    public static function errorServiceResponse(
        int $code_status,
        bool $status,
        string $message = '',
        $errors = null
    ): array {
        return [
            'code_status' => $code_status,
            'status' => $status,
            'message' => $message,
            'errors' => $errors,
        ];
    }
}