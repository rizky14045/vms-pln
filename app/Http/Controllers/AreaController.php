<?php

namespace App\Http\Controllers;

use App\FormatRequest\FormatRequest;
use App\Validation\AreaValidation;
use App\Services\AreaService;
use App\Services\VaultSiteService;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Calculation\TextData\Format;

class AreaController extends Controller
{
    protected $vaultSiteService, $formatRequest, $areaService;

    public function __construct(
        VaultSiteService $vaultSiteService,
        FormatRequest $formatRequest,
        AreaService $areaService
    ) {
        $this->vaultSiteService = $vaultSiteService;
        $this->formatRequest = $formatRequest;
        $this->areaService = $areaService;
    }

    protected function validator(array $data, $validation, array $messages = [])
    {
        return Validator::make($data, $validation, $messages);
    }

    public function index(): View {
        return view('pages.areas.index', [
            'areas' => []
        ]);
    }

    public function create(): View {
        return view('pages.areas.create', [
            'areas' => []
        ]);
    }
    
    public function store(Request $request) {
        // Validate request data
        $validator = $this->validator($request->all(), AreaValidation::rulesForCreate(), AreaValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        };

        $accessNo = $this->areaService->getNextAvailableAccessNo();
        if ($accessNo === null) {
            return redirect()->back()->withErrors('Kontroller sudah penuh.')->withInput();
        }
        $request->merge(['access_no' => $accessNo]);

        $stored_area = $this->areaService->createArea($request->all());

        if (!$stored_area["status"]) {
            return redirect()->back()->withErrors($stored_area["message"])->withInput();
        }

        return redirect()->route('areas.index')->with('success', 'Area berhasil dibuat.');
    }
}
