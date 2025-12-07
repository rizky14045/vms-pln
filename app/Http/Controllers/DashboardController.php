<?php

namespace App\Http\Controllers;

use DOMXPath;
use Exception;
use DOMDocument;
use SimpleXMLElement;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function index(): View {
        return view('dashboard');
    }
}
