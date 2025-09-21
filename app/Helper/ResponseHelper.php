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
        string $message = '',
        $data = null
    ): array {
        return [
            'code_status' => 200,
            'status' => true,
            'message' => $message,
            'data' => $data,
        ];
    }

    public static function errorServiceResponse(
        int $code_status,
        string $message = '',
        $errors = null
    ): array {
        return [
            'code_status' => $code_status,
            'status' => false,
            'message' => $message,
            'errors' => $errors,
        ];
    }
}