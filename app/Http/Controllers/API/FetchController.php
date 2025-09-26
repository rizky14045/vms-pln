<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class FetchController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}
    
    public function getUserByNid($nid) {
        
        try {
            $user = $this->userService->getUserByNid($nid);
            if(!$user){
                return ResponseHelper::response(
                    false,
                    'Data karyawan belum terdaftar!', // messages
                    null, // errors
                    null, // data
                    404 // status_code
                );
            }
            $data['user'] = $user;
            return ResponseHelper::response(
                true,
                'Data Karyawan Ditemukan!', // messages
                null, // errors
                $data, // data
                200 // status_code
            );
            
        }catch (\Throwable $th) {
            return ResponseHelper::response(
                false,
                'Error Internal Server', // messages
                $th->getMessage(), // errors
                null, // data
                500 // status_code
            );
        }
    }

    
}
