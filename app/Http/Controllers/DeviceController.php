<?php

namespace App\Http\Controllers;

use App\Services\DeviceService;
use App\Validation\DeviceValidation;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;

class DeviceController extends Controller
{
    protected $deviceService;

    public function __construct(
        DeviceService $deviceService
    ) {
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

        // Call service to get devices with filters
        $areasResponse = $this->deviceService->getAllDevices($filters);

        if (!$areasResponse['status']) {
            return redirect('login')->withErrors($areasResponse['message']);
        }

        // Get data areas
        $devices = $areasResponse['data'] ?? [];

        return view('pages.devies.index', [
            'devices'   => $devices,
            'filters' => $filters,
        ]);
    }

    public function create(): View {
        return view('pages.devices.create', [
            'areas' => []
        ]);
    }
    
    public function store(Request $request) {
        // Validate request data
        $validator = $this->validator($request->all(), DeviceValidation::rulesForCreate(), DeviceValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        };

        $stored_area = $this->deviceService->createDevice($request->all());

        if (!$stored_area["status"]) {
            return redirect()->back()->withErrors($stored_area["message"])->withInput();
        }

        return redirect()->route('areas.index')->with('success', 'Area berhasil dibuat.');
    }
}
