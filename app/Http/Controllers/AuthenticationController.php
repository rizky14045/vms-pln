<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Service\UserService;
use Illuminate\Http\Request;

class AuthenticationController extends Controller
{

    public function __construct(
        protected UserService $userService
    ) {}
    
    public function viewLogin(): View {
        return view('login');
    }
    public function viewRegister(): View {
        return view('register');
    }
}
