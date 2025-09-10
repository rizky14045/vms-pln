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
}