<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Services\AreaService;
use App\Services\VaultSiteService;
use App\Services\RegisterPersonService;
use App\FormatRequest\FormatRequestVaultsite;

class RegisteredController extends Controller
{
     public function __construct(
        protected RegisterPersonService $registerPersonService,
        protected AreaService $areaService,
        protected VaultSiteService $vaultSiteService
    ) {}

    public function index(): View {
        $data['registeredPersons'] = $this->registerPersonService->getAllRegisteredPerson();
        return view('pages.registered.index', $data);
    }

    public function show($id): View {
        $data['registeredPerson'] = $this->registerPersonService->getRegisteredPersonById($id);
        $get_areas = $this->areaService->getAllAreas(['limit' => 1000]);

        if ($get_areas['status']) {
            $areas = $get_areas['data'] ?? [];
        } else {
            $areas = [];
        }

        $data['areas'] = $areas; // ✅ kirim yang benar ke view
        return view('pages.registered.show', $data);
    }

    public function approve($id) {
        $data['registeredPerson'] = $this->registerPersonService->getRegisteredPersonById($id);
        $get_areas = $this->areaService->getAllAreas(['limit' => 1000]);

        if ($get_areas['status']) {
            $areas = $get_areas['data'] ?? [];
        } else {
            $areas = [];
        }

        $data['areas'] = $areas; // ✅ kirim yang benar ke view
        return view('pages.registered.approve', $data);
    }

    public function updateApprove(Request $request,$id) {

        $registeredPerson = $this->registerPersonService->getRegisteredPersonById($id);
        $formatRequest = FormatRequestVaultsite::formatAddCard($registeredPerson);
        $response = $this->vaultSiteService->addCard($formatRequest);

        dd($response);
    }

  
    
}
