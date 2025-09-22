<?php

namespace App\Http\Controllers;

use App\Validation\AuthValidation;
use App\Services\AuthService;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{    
    protected $authService;

    public function __construct(
        AuthService $authService
    ) {
        $this->authService = $authService;
    }

    protected function validator(array $data, $validation, array $messages = [])
    {
        return Validator::make($data, $validation, $messages);
    }

    public function viewLogin(): View {
        return view('login');
    }
    public function viewRegister(): View {
        return view('register');
    }

    public function login(Request $request) {
        
        $validator = $this->validator($request->all(), AuthValidation::rulesForLogin(), AuthValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        };

        try {
            $this->authService->login(
                $request->only('email', 'password'),
                $request->boolean('remember')
            );

            $request->session()->regenerate();

            return redirect()->intended('/dashboard')
                ->with('success', 'Login berhasil!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }
}
