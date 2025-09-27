<?php

namespace App\Http\Controllers;

use App\FormatRequest\FormatRequestVaultsite;
use App\Models\Device;
use App\Validation\AreaValidation;
use App\Services\AreaService;
use App\Services\DeviceService;
use App\Services\VaultSiteService;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Calculation\TextData\Format;

class AreaController extends Controller
{
    protected $vaultSiteService, $formatRequest, $areaService, $deviceService;

    public function __construct(
        VaultSiteService $vaultSiteService,
        FormatRequestVaultsite $formatRequest,
        AreaService $areaService,
        DeviceService $deviceService
    ) {
        $this->vaultSiteService = $vaultSiteService;
        $this->formatRequest = $formatRequest;
        $this->areaService = $areaService;
        $this->deviceService = $deviceService;
    }

    protected function validator(array $data, $validation, array $messages = [])
    {
        return Validator::make($data, $validation, $messages);
    }

    public function index(Request $request)
    {
        // Get filter from request
        $filters = [
            'start_date' => $request->input('start_date'),
            'end_date'   => $request->input('end_date'),
            'search'     => $request->input('search'),
            'order'      => $request->input('order', 'desc'),
            'orderby'    => $request->input('orderby', 'created_at'),
        ];

        // Call service to get areas with filters
        $areasResponse = $this->areaService->getAllAreas($filters);

        if (!$areasResponse['status']) {
            return redirect('login')->withErrors($areasResponse['message']);
        }

        // Get data areas
        $areas = $areasResponse['data'] ?? [];

        return view('pages.areas.index', [
            'areas'   => $areas,
            'filters' => $filters,
        ]);
    }

    public function create() {
        $devices = $this->deviceService->getAllDevices(['status' => 'active'], false);
        if (!$devices["status"]) {
            return redirect()->back()->withErrors($devices["message"]);
        }

        return view('pages.areas.create', [
            'devices' => $devices["data"]
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

        DB::beginTransaction();

        $stored_area = $this->areaService->createArea($request->all());

        if (!$stored_area["status"]) {
            DB::rollBack();
            return redirect()->back()->withErrors($stored_area["message"])->withInput();
        }
        
        $stored_device_area = $this->areaService->createDeviceArea($stored_area["data"]->id, $request->all());

        if (!$stored_device_area["status"]) {
            DB::rollBack();
            return redirect()->back()->withErrors($stored_device_area["message"])->withInput();
        }

        $processArea = $this->areaService->processAreas();

        DB::commit();

        return redirect()->route('areas.index')->with('success', 'Area berhasil dibuat.');
    }

    public function edit($id) {
        $area = $this->areaService->getAreaById($id, false, true);
        if (!$area["status"]) {
            return redirect()->back()->withErrors($area["message"]);
        }

        $devices = $this->deviceService->getAllDevices(['status' => 'active'], false);
        if (!$devices["status"]) {
            return redirect()->back()->withErrors($devices["message"]);
        }

        return view('pages.areas.edit', [
            'area' => $area["data"],
            'devices' => $devices["data"]
        ]);
    }

    public function update(Request $request, $id) {
        // Validate request data
        $validator = $this->validator($request->all(), AreaValidation::rulesForUpdate(), AreaValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        };

        DB::beginTransaction();

        $stored_area = $this->areaService->updateArea($id, $request->all());

        if (!$stored_area["status"]) {
            DB::rollBack();
            return redirect()->back()->withErrors($stored_area["message"])->withInput();
        }

        $stored_device_area = $this->areaService->createDeviceArea($stored_area["data"]->id, $request->all());

        if (!$stored_device_area["status"]) {
            DB::rollBack();
            return redirect()->back()->withErrors($stored_device_area["message"])->withInput();
        }

        $processArea = $this->areaService->processAreas();

        DB::commit();

        return redirect()->route('areas.index')->with('success', 'Area berhasil diedit.');
    }
}
