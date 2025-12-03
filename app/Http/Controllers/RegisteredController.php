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
use Carbon\Carbon;

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

    public function indexVisitor(): View {
        return view('pages.registered.index-visitor');
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
        $data['areas'] = $this->areaService->getAllAreas(['limit' => 1000], "employee")['data'] ;

        return view('pages.registered.approve', $data);
    }

    public function approveVisitor($id) {
        $data['registeredPerson'] = $this->registerPersonService->getRegisteredPersonById($id);
        $data['areas'] = $this->areaService->getAllAreas(['limit' => 1000], "visitor")['data'] ;

        return view('pages.registered.approve-visitor', $data);
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

    public function updateApproveVisitor(Request $request,$id) {

        try {
           if($request->action === 'approve'){
                return $this->approveRegistered($request, $id);
            }elseif($request->action === 'reject'){
                return $this->rejectRegistered($id);
            }else{
                Alert::error('Error', 'Action tidak valid');
                return redirect()->route('registered.index.visitor');
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
    public function getDataIndex(Request $request)
    {
        $isEmployee = $request->query('is_employee', 0);

        if (!in_array($isEmployee, [0, 1, '0', '1'], true)) {
            return response()->json([
                'error' => true,
                'message' => 'Parameter is_employee hanya boleh 0 atau 1.'
            ], 400);
        }
        
        $query = $this->registerPersonService->getAllRegisteredPerson($isEmployee);

        return \DataTables::of($query)
            ->addIndexColumn() // nomor otomatis
            ->addColumn('nid', fn($row) => $row->user->nid ?? '')
            ->addColumn('name', fn($row) => $row->user->name ?? '')
            ->addColumn('company', function ($row) use ($isEmployee) {
                if ($isEmployee == 0) {
                    return $row->user->company ?? '-';
                }
                return '-'; // employee tidak punya company
            })
            ->addColumn('status', function ($row) {
                if ($row->status_level == 1) {
                    return '<span class="bg-warning-100 dark:bg-warning-600/25 text-warning-600 dark:text-warning-400 px-6 py-1.5 rounded-full font-medium text-sm">'.$row->status.'</span>';
                } elseif ($row->status_level == 0) {
                    return '<span class="bg-danger-100 dark:bg-danger-600/25 text-danger-600 dark:text-danger-400 px-6 py-1.5 rounded-full font-medium text-sm">'.$row->status.'</span>';
                }
                return '<span class="bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 px-6 py-1.5 rounded-full font-medium text-sm">'.$row->status.'</span>';
            })
            ->addColumn('action', function ($row) use ($isEmployee) {
                if ($row->status_level == 1) {
                    if($isEmployee){
                        $approveUrl = route('registered.approve', $row->id);
                    }else{
                        $approveUrl = route('registered.approve.visitor', $row->id);
                    }
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
        $isEmployee = request()->query('is_employee', 1);
        //check area not null
        if($request->area_id == null){
            Alert::warning('Warning', 'Area tidak boleh kosong');
            return redirect()->back();
        }
        if(!$isEmployee){
            if($request->expired_at == null){
                Alert::warning('Warning', 'Tanggal kedaluwarsa tidak boleh kosong');
                return redirect()->back();
            }

            if(!strtotime($request->expired_at)){
                Alert::warning('Warning', 'Format tanggal kedaluwarsa tidak valid');
                return redirect()->back();
            }

            if(strtotime($request->expired_at) <= strtotime(now())){
                Alert::warning('Warning', 'Tanggal kedaluwarsa harus lebih besar dari hari ini');
                return redirect()->back();
            }
        }
        $registeredPerson = $this->registerPersonService->getRegisteredPersonById($id);
        $userRegistered = $this->userService->getUserById($registeredPerson->user->id);
        $areaAccessNumber = $this->areaService->getAreaAccessNumber($request->area_id);
        
        $formatRequest = FormatRequestVaultsite::formatAddCard($registeredPerson, $areaAccessNumber->access_no);

        //check if user already have card
        if($userRegistered->is_registered == false){
            $this->userService->updateStatusRegistered($userRegistered);
            $response = $this->vaultSiteService->addCard($formatRequest);
            $formatRequest = FormatRequestVaultsite::formatAddFace($userRegistered->id_card_number);
            $response = $this->vaultSiteService->addToFR($formatRequest);
        }else{
            $response = $this->vaultSiteService->updateCard($userRegistered->id_card_number, $formatRequest);
            $formatRequest = FormatRequestVaultsite::formatAddFace($userRegistered->id_card_number);
            $response = $this->vaultSiteService->addToFR($formatRequest);
            // $data = [
            //     'CardNo' => (string) $userRegistered->id_card_number,
            //     'AccessLevel' => (string) $areaAccessNumber->access_no,
            //     'DownloadCard' => "true"
            // ];
            // $response = $this->vaultSiteService->updateCardAccessLevel($data);

            
        }

        if(!$isEmployee){
            $response = $this->vaultSiteService->updateCardExpiryDate($userRegistered->id_card_number, date('Y-m-d\TH:i:s', strtotime($request->expired_at)));
        }
        
        $this->registerPersonService->updateAreaId($registeredPerson, $request->area_id);
        $updateData = [
            'status' => 'Approved',
            'status_level' => 2
        ];
        if(!$isEmployee){
            $updateData['expired_at'] = date('Y-m-d H:i:s', strtotime($request->expired_at));
        }

        $this->registerPersonService->updateStatusRegisteredPerson($registeredPerson, $updateData);

        if($isEmployee){
            Alert::success('Success', 'Berhasil menyetujui registrasi karyawan');
            return redirect()->route('registered.index');
        }
        Alert::success('Success', 'Berhasil menyetujui registrasi kunjungan');
        return redirect()->route('registered.index.visitor');
    }
    protected function rejectRegistered($id)
    {
        $isEmployee = request()->query('is_employee', 1);
        $registeredPerson = $this->registerPersonService->getRegisteredPersonById($id);
        $this->registerPersonService->updateStatusRegisteredPerson($registeredPerson, ['status' => 'Rejected','status_level' => 0]);

        
        if($isEmployee){
            Alert::success('Success', 'Berhasil menolak registrasi karyawan');
            return redirect()->route('registered.index');
        }
        Alert::success('Success', 'Berhasil menolak registrasi kunjungan');
        return redirect()->route('registered.index.visitor');
    }
}
