<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\View\View;
use App\Service\UserService;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthenticationController extends Controller
{

    public function __construct(
        protected UserService $userService
    ) {}
    
    public function login(Request $request) {
        
        try {
            $validated = $request->validate([
                'email' => 'required|email:rfc,dns',
                'password' => 'required'
            ],[
                'email.required' => 'Email Harus Diisi',
                'email.email' => 'Format email salah!',
                'password.required' => 'Password Harus Diisi',
            ]);
            $user = $this->userService->getUserByEmail($request->email);
            if(!$user){
                return ResponseHelper::response(
                    false,
                    'User tidak ditemukan!', // messages
                    null, // errors
                    null, // data
                    404 // status_code
                );
            }
            if( Hash::check($request->password,$user->password) ){

                $token = JWTAuth::fromUser($user);

                $data = [
                    'login_type' => 'login',
                    'access_token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => Auth::guard('api')->factory()->getTTL() * 60
                ];

                return ResponseHelper::response(
                    true,
                    'Login berhasil!', // messages
                    null, // errors
                    $data, // data
                    200 // status_code
                );
    
            }else{
                
                return ResponseHelper::response(
                    false,
                    'Email atau kata sandi salah!', // messages
                    null, // errors
                    null, // data
                    404 // status_code
                );
    
            }
        }catch (ValidationException $e) {
            DB::rollback();
            return ResponseHelper::response(
                false,
                'Input tidak valid!',
                $e->errors(),
                null,
                422
            );
        }
         catch (\Throwable $th) {
            return ResponseHelper::response(
                false,
                'Error Internal Server', // messages
                $th->getMessage(), // errors
                null, // data
                500 // status_code
            );
        }
    }

    public function register(Request $request){

    try {
        DB::beginTransaction();

        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|unique:users|email:rfc,dns',
            'password' => 'required',
        ],[
            'name.required' => 'Nama Harus Diisi!',
            'name.max' => 'Nama tidak boleh lebih dari 255 karakter',
            'email.required' => 'Email Harus Diisi',
            'email.unique' => 'Email sudah pernah terdaftar!',
            'email.email' => 'Format email salah!',
            'password.required' => 'Password Harus Diisi',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $data['user'] = $user;
        DB::commit();
        return ResponseHelper::response(
            true,
            'Berhasil diregistrasi!', // messages
            null, // errors
            $data, // data
            200 // status_code
        );
        
     } catch (ValidationException $e) {
        DB::rollback();
        return ResponseHelper::response(
            false,
            'Input tidak valid!',
            $e->errors(),
            null,
            422
        );
    }catch (\Throwable $th) {

        DB::rollback();
        return ResponseHelper::response(
            false,
            'Error Internal Server', // messages
            $th->getMessage(), // errors
            null, // data
            500 // status_code
        );

     }
    }
    
    protected function validateRegister(array $request)
    {

        $rules = [
            'name' => 'required|max:255',
            'email' => 'required|unique:users|email:rfc,dns',
            'password' => 'required',
        ];
        $messages = [
            'name.required' => 'Nama Harus Diisi!',
            'name.max' => 'Nama tidak boleh lebih dari 255 karakter',
            'email.required' => 'Email Harus Diisi',
            'email.unique' => 'Email sudah pernah terdaftar!',
            'email.email' => 'Format email salah!',
            'password.required' => 'Password Harus Diisi',
        ];

        $validator = validator($request, $rules,$messages);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    public function me():JsonResponse {
        try {
            
            $data['user'] = Auth::guard('api')->user();
            return ResponseHelper::response(
                true,
                'Data berhasil didapatkan!', // messages
                null, // errors
                $data, // data
                200 // status_code
            );
        }
         catch (\Throwable $th) {
            return ResponseHelper::response(
                false,
                'Error Internal Server', // messages
                $th->getMessage(), // errors
                null, // data
                500 // status_code
            );
        }
    }
    public function refresh():JsonResponse {
        try {
            
            $token = Auth::guard('api')->refresh();
            $data = [
                'login_type' => 'login',
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => Auth::guard('api')->factory()->getTTL() * 60
            ];
            return ResponseHelper::response(
                true,
                'Data berhasil didapatkan!', // messages
                null, // errors
                $data, // data
                200 // status_code
            );
        }
         catch (\Throwable $th) {
            return ResponseHelper::response(
                false,
                'Error Internal Server', // messages
                $th->getMessage(), // errors
                null, // data
                500 // status_code
            );
        }
    }
    public function logout():JsonResponse {
        try {
            
            Auth::guard('api')->logout();
            return ResponseHelper::response(
                true,
                'Logout berhasil!', // messages
                null, // errors
                null, // data
                200 // status_code
            );
        }
         catch (\Throwable $th) {
            return ResponseHelper::response(
                true,
                'Error Internal Server', // messages
                $th->getMessage(), // errors
                null, // data
                500 // status_code
            );
        }
    }
}
