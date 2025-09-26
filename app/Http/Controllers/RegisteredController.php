<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Services\AreaService;
use App\Services\UserService;
use App\Services\VaultSiteService;
use App\Services\RegisterPersonService;
use RealRashid\SweetAlert\Facades\Alert;
use App\FormatRequest\FormatRequestVaultsite;

class RegisteredController extends Controller
{
     public function __construct(
        protected RegisterPersonService $registerPersonService,
        protected AreaService $areaService,
        protected VaultSiteService $vaultSiteService,
        protected UserService $userService
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

        try {
            $registeredPerson = $this->registerPersonService->getRegisteredPersonById($id);
            $formatRequest = FormatRequestVaultsite::formatAddCard($registeredPerson);
            //check if user already have card
            $userRegistered = $this->userService->getUserById($registeredPerson->user->id);
            if($userRegistered->is_registered == false){
                $this->userService->updateStatusRegistered($userRegistered);
                $response = $this->vaultSiteService->addCard($formatRequest);
            }else{

                $data = [
                    'CardNo' => (string) $userRegistered->id_card_number,
                    'AccessLevel' => "03",
                    'DownloadCard' => "true"
                ];
                $response = $this->vaultSiteService->updateCardAccessLevel($data);
            }
            $this->registerPersonService->updateStatusRegisteredPerson($registeredPerson, ['status' => 'Approved','status_level' => 2]);

            Alert::success('Success', 'Berhasil menyetujui registrasi kunjungan');
            return redirect()->route('registered.index');
        } catch (\Throwable $th) {
            throw $th;
        }
        

    }

  
    
}
