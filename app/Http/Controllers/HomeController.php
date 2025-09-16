<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(): View {
        return view('home');
    }
    public function registerVisitor(): View {
        return view('register-visitor');
    }
}
