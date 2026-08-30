<?php

namespace App\Http\Controllers;

use App\Exports\VisitorCardHistoriesExport;
use App\Services\VisitorCardService;
use App\Validation\VisitorCardValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class VisitorCardController extends Controller
{
    protected $visitorCardService;

    public function __construct(VisitorCardService $visitorCardService)
    {
        $this->visitorCardService = $visitorCardService;

        // Kelola master kartu & riwayat kartu hanya untuk yang punya permission ini.
        // returnCards SENGAJA tidak digate di sini karena dipakai dari halaman
        // registered-visitor yang bisa diakses siapapun dengan permission 'approval visitor'.
        $this->middleware('permission:manage visitor card')->only([
            'index', 'create', 'store', 'edit', 'update', 'histories', 'historyExport',
        ]);
    }

    protected function validator(array $data, $validation, array $messages = [])
    {
        return Validator::make($data, $validation, $messages);
    }

    public function index(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'status' => $request->input('status'),
        ];

        $cardsResponse = $this->visitorCardService->getAllCards($filters);

        if (!$cardsResponse['status']) {
            return redirect()->back()->withErrors($cardsResponse['message']);
        }

        return view('pages.visitor-cards.index', [
            'cards' => $cardsResponse['data'],
            'filters' => $filters,
        ]);
    }

    public function create(): View
    {
        return view('pages.visitor-cards.create');
    }

    public function store(Request $request)
    {
        $validator = $this->validator($request->all(), VisitorCardValidation::rulesForCreate(), VisitorCardValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $stored = $this->visitorCardService->createCard($request->all());
        if (!$stored['status']) {
            return redirect()->back()->withErrors($stored['message'])->withInput();
        }

        return redirect()->route('visitor-cards.index')->with('success', 'Kartu visitor berhasil dibuat.');
    }

    public function edit($id)
    {
        $cardResponse = $this->visitorCardService->getCardById($id);
        if (!$cardResponse['status']) {
            return redirect()->route('visitor-cards.index')->withErrors($cardResponse['message']);
        }

        return view('pages.visitor-cards.edit', [
            'card' => $cardResponse['data'],
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = $this->validator($request->all(), VisitorCardValidation::rulesForUpdate($id), VisitorCardValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $stored = $this->visitorCardService->updateCard($id, $request->all());
        if (!$stored['status']) {
            return redirect()->back()->withErrors($stored['message'])->withInput();
        }

        return redirect()->route('visitor-cards.index')->with('success', 'Kartu visitor berhasil diedit.');
    }

    public function histories(Request $request)
    {
        $filters = [
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
        ];

        $historiesResponse = $this->visitorCardService->getAllHistories($filters);

        if (!$historiesResponse['status']) {
            return redirect()->back()->withErrors($historiesResponse['message']);
        }

        return view('pages.visitor-cards.histories', [
            'histories' => $historiesResponse['data'],
            'filters' => $filters,
        ]);
    }

    public function historyExport(Request $request)
    {
        $filters = [
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
        ];

        $historiesResponse = $this->visitorCardService->getAllHistories($filters);

        $fileName = 'riwayat-kartu-visitor-' . now()->format('Y-m-d_His') . '.xlsx';

        return Excel::download(new VisitorCardHistoriesExport($historiesResponse['data']), $fileName);
    }

    public function returnCards(Request $request)
    {
        $cardIds = $request->input('card_ids', []);

        if (empty($cardIds)) {
            Alert::warning('Warning', 'Pilih minimal satu kartu untuk dikembalikan.');
            return redirect()->back();
        }

        $result = $this->visitorCardService->returnCards($cardIds);

        if (!$result['status']) {
            Alert::error('Error', $result['message']);
            return redirect()->back();
        }

        Alert::success('Success', 'Kartu berhasil dikembalikan.');
        return redirect()->back();
    }
}
