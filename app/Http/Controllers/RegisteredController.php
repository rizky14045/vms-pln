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
        return view('pages.registered.index');
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
        $data['areas'] = $this->areaService->getAllAreas(['limit' => 1000])['data'] ;

        return view('pages.registered.approve', $data);
    }

    public function updateApprove(Request $request,$id) {

        try {
           if($request->action === 'approve'){
                return $this->approveRegistered($request, $id);
            }elseif($request->action === 'reject'){
                return $this->rejectRegistered($id);
            }else{
                Alert::error('Error', 'Action tidak valid');
                return redirect()->route('registered.index');
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
    public function getDataIndex(Request $request)
    {
        $query = $this->registerPersonService->getAllRegisteredPerson();

        return \DataTables::of($query)
            ->addIndexColumn() // nomor otomatis
            ->addColumn('nid', fn($row) => $row->user->nid ?? '')
            ->addColumn('name', fn($row) => $row->user->name ?? '')
            ->addColumn('status', function ($row) {
                if ($row->status_level == 1) {
                    return '<span class="bg-warning-100 dark:bg-warning-600/25 text-warning-600 dark:text-warning-400 px-6 py-1.5 rounded-full font-medium text-sm">'.$row->status.'</span>';
                } elseif ($row->status_level == 0) {
                    return '<span class="bg-danger-100 dark:bg-danger-600/25 text-danger-600 dark:text-danger-400 px-6 py-1.5 rounded-full font-medium text-sm">'.$row->status.'</span>';
                }
                return '<span class="bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 px-6 py-1.5 rounded-full font-medium text-sm">'.$row->status.'</span>';
            })
            ->addColumn('action', function ($row) {
                if ($row->status_level == 1) {
                    $approveUrl = route('registered.approve', $row->id);
                    return '<a href="'.$approveUrl.'" class="w-8 h-8 bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 rounded-full inline-flex items-center justify-center">
                                <iconify-icon icon="solar:check-circle-outline"></iconify-icon>
                            </a>';
                }
                return '';
            })
        ->rawColumns(['status', 'action'])
        ->make(true);
    }
    protected function approveRegistered(Request $request, $id)
    {
        //check area not null
        if($request->area_id == null){
            Alert::warning('Warning', 'Area tidak boleh kosong');
            return redirect()->back();
        }
        $registeredPerson = $this->registerPersonService->getRegisteredPersonById($id);
        $userRegistered = $this->userService->getUserById($registeredPerson->user->id);
        $areaAccessNumber = $this->areaService->getAreaAccessNumber($request->area_id);
        
        $formatRequest = FormatRequestVaultsite::formatAddCard($registeredPerson, $areaAccessNumber->access_no);
        
        //check if user already have card
        if($userRegistered->is_registered == false){
            $this->userService->updateStatusRegistered($userRegistered);
            $response = $this->vaultSiteService->addCard($formatRequest);
        }else{
            
            $data = [
                'CardNo' => (string) $userRegistered->id_card_number,
                'AccessLevel' => (string) $areaAccessNumber->access_no,
                'DownloadCard' => "true"
            ];
            $response = $this->vaultSiteService->updateCardAccessLevel($data);
        }
        $this->registerPersonService->updateAreaId($registeredPerson, $request->area_id);
        $this->registerPersonService->updateStatusRegisteredPerson($registeredPerson, ['status' => 'Approved','status_level' => 2]);

        Alert::success('Success', 'Berhasil menyetujui registrasi kunjungan');
        return redirect()->route('registered.index');
    }
    protected function rejectRegistered($id)
    {
        $registeredPerson = $this->registerPersonService->getRegisteredPersonById($id);
        $this->registerPersonService->updateStatusRegisteredPerson($registeredPerson, ['status' => 'Rejected','status_level' => 0]);

        Alert::success('Success', 'Berhasil menolak registrasi kunjungan');
        return redirect()->route('registered.index');
    }
}
