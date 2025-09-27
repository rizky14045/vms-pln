<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Services\AreaService;
use App\Services\DeviceService;
use App\Validation\DeviceValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;

class DeviceController extends Controller
{
    protected $deviceService, $areaService;

    public function __construct(
        DeviceService $deviceService,
        AreaService $areaService
    ) {
        $this->deviceService = $deviceService;
        $this->areaService = $areaService;
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

        // Call service to get devices with filters
        $devicesResponse = $this->deviceService->getAllDevices($filters, false);

        if (!$devicesResponse['status']) {
            return redirect('login')->withErrors($devicesResponse['message']);
        }

        // Get data areas
        $devices = $devicesResponse['data'] ?? [];

        return view('pages.devices.index', [
            'devices'   => $devices,
            'filters' => $filters,
        ]);
    }

    public function create(): View {
        return view('pages.devices.create');
    }
    
    public function store(Request $request) {
        // Validate request data
        $validator = $this->validator($request->all(), DeviceValidation::rulesForCreate(), DeviceValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        };

        DB::beginTransaction();
        $stored_area = $this->deviceService->createDevice($request->all());

        if (!$stored_area["status"]) {
            DB::rollBack();
            return redirect()->back()->withErrors($stored_area["message"])->withInput();
        }

        DB::commit();

        return redirect()->route('devices.index')->with('success', 'Device berhasil dibuat.');
    }

    public function edit($id) {
        $getDevice = $this->deviceService->getDeviceById($id);
        if (!$getDevice['status']) {
            return redirect()->route('devices.index')->withErrors($getDevice['message']);
        }
        $device = $getDevice['data'] ?? null;

        return view('pages.devices.edit', [
            'device' => $device
        ]);
    }

    public function update(Request $request, $id) {
        // Validate request data
        $validator = $this->validator($request->all(), DeviceValidation::rulesForUpdate(), DeviceValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        };

        DB::beginTransaction();
        $stored_area = $this->deviceService->updateDevice($id, $request->all());

        if (!$stored_area["status"]) {
            DB::rollBack();
            return redirect()->back()->withErrors($stored_area["message"])->withInput();
        }

        DB::commit();

        return redirect()->route('devices.index')->with('success', 'Device berhasil diedit.');
    }
}
