<?php

namespace App\Http\Controllers;

use App\FormatRequest\FormatRequest;
use App\Http\Validation\AreaValidation;
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
    
    public function store(Request $request) {
        // Validate request data
        $validator = $this->validator($request->all(), AreaValidation::rulesForCreate(), AreaValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        };

        $stored_area = $this->areaService->createArea($request->all());

        if (!$stored_area["status"]) {
            return redirect()->back()->withErrors($stored_area["message"])->withInput();
        }

        $result = $this->vaultSiteService->addArea($this->formatRequest->formatAddArea($stored_area["data"]->access_no, $request->all()));

        return redirect()->route('area.index')->with('success', 'Area berhasil dibuat.');
    }

    public function destroy($accessNo) {
        $result = $this->vaultSiteService->deleteArea($accessNo);

        dd($result);
    }
}
