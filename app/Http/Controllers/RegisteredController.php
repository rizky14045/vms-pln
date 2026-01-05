<?php

namespace App\Http\Controllers;

use App\FormatRequest\FormatRequestUser;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Services\AreaService;
use App\Services\UserService;
use App\Services\VaultSiteService;
use App\Services\RegisterPersonService;
use RealRashid\SweetAlert\Facades\Alert;
use App\FormatRequest\FormatRequestVaultsite;
use App\Helper\FileHelper;
use App\Models\RegisteredPerson;
use App\Validation\RegisterRequestValidation;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Colors\Rgb\Channels\Red;

class RegisteredController extends Controller
{
    public function __construct(
        protected RegisterPersonService $registerPersonService,
        protected AreaService $areaService,
        protected VaultSiteService $vaultSiteService,
        protected UserService $userService,
        protected FormatRequestUser $formatRequestUser,
    ) {}

    public function index(Request $request) {
        $filters = [
            'is_employee' => 1,
            'search'      => $request->search,
            'order_by'    => in_array($request->order_by, ['created_at', 'name', 'status_level'])
                                ? $request->order_by
                                : 'created_at',
            'order_dir'   => $request->order_dir === 'asc' ? 'asc' : 'desc',
        ];

        $data['visitors'] = $this->registerPersonService
            ->getAllRegisteredPerson($filters);
        return view('pages.registered.index', $data);
    }

    public function indexVisitor(Request $request)
    {
        $filters = [
            'is_employee' => 0,
            'search'      => $request->search,
            'order_by'    => in_array($request->order_by, ['created_at', 'name', 'status_level'])
                                ? $request->order_by
                                : 'created_at',
            'order_dir'   => $request->order_dir === 'asc' ? 'asc' : 'desc',
        ];

        $data['visitors'] = $this->registerPersonService
            ->getAllRegisteredPerson($filters);

        return view('pages.registered.index-visitor', $data);
    }



    public function createVisitor(): View {
        $data['areas'] = $this->areaService->getAllAreas(['limit' => 1000], "visitor")['data'] ;
        return view('pages.registered.create-visitor', $data);
    }

    public function storeVisitor(Request $request) {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), RegisterRequestValidation::rulesForCreateVisitor(), RegisterRequestValidation::messages());
            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            //check if user exist by nid
            $user = $this->userService->getUserByNid($request->nid);
            if(!$user) {
                $request->merge(['is_employee' => false]);
                $formatRequest = $this->formatRequestUser->employeeUser($request->all()); 
                $user = $this->userService->createUser($formatRequest);
            }
            
            // get get latest registered person
            $latestRegisteredPerson = RegisteredPerson::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->first();

            if($latestRegisteredPerson != null){
                if ($latestRegisteredPerson->expired_at && $latestRegisteredPerson->expired_at > now()) {
                    return redirect()->route('registered.create-visitor')->with('info', 'Visitor masih berlaku hingga ' . $latestRegisteredPerson->expired_at->format('d-m-Y') . '.');
                }
            }
            
            if ($request->hasFile('person_image')) {
                $base64Only = FileHelper::toResizedBase64(
                    $request->file('person_image'),
                    false // tanpa prefix
                );
                $result = $this->vaultSiteService->checkFacePhoto($base64Only);

                if ($result['error']) {
                    return redirect()
                        ->back()
                        ->withErrors([
                            'person_image' => $result['message'] // tampil di input person_image
                        ])
                        ->withInput();
                }
            }else {
                 return redirect()->route('registered.create-visitor')->with('info', 'Foto tidak ditemukan!');
            }

            $getFilename = FileHelper::generatedFileName('Person', $request->person_image->extension());
            $request->merge(['image_name' => $getFilename,'user_id' => $user->id, 'is_employee' => false]);
            
            $createdRegister = $this->registerPersonService->createRegisteredPerson($request->all());
            $base64WithPrefix = FileHelper::toResizedBase64(
                $request->file('person_image'),
                true // default sebenarnya true
            );
        
            $base64 = FileHelper::toResizedBase64($request->file('person_image'), false);

            $dataBinary = base64_decode($base64);

            file_put_contents(
                public_path('uploads/person_images/' . $getFilename),
                $dataBinary
            );
            DB::commit();

            $this->approveRegistered($request, $createdRegister->id);
            RegisteredPerson::where("user_id",$user->id)->where("status_level", 1)->update([
                "status_level" => 0,
                "status" => "Rejected"
            ]);
            return redirect()->route('registered.create-visitor')->with('success', 'Berhasil melakukan registrasi kunjungan.');

        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
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

    public function edit($id) {
        $data['registeredPerson'] = $this->registerPersonService->getRegisteredPersonById($id);
        if(!$data['registeredPerson']->is_employee || $data['registeredPerson']->status_level != 2) {
            return abort(404);
        } 
        $data['areas'] = $this->areaService->getAllAreas(['limit' => 1000], "employee")['data'] ;

        return view('pages.registered.edit', $data);
    }

    public function updateCard(Request $request,$id) {
        try {
            $registeredPerson = $this->registerPersonService->getRegisteredPersonById($id);
            $user = $this->userService->getUserById($registeredPerson->user->id);

            $validator = Validator::make($request->all(), RegisterRequestValidation::rulesForEditEmployee($user->id), RegisterRequestValidation::messages());
            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            
            if ($request->hasFile('person_image')) {
                $base64Only = FileHelper::toResizedBase64(
                    $request->file('person_image'),
                    false // tanpa prefix
                );
                $result = $this->vaultSiteService->checkFacePhoto($base64Only);

                if ($result['error']) {
                    return redirect()
                        ->back()
                        ->withErrors([
                            'person_image' => $result['message'] 
                        ])
                        ->withInput();
                }

                $getFilename = FileHelper::generatedFileName('Person', $request->person_image->extension());
                $request->merge(['image_name' => $getFilename, 'is_registered' => false]);
                $this->registerPersonService->updateStatusRegisteredPersonById($id, $request->all());
                $this->userService->updateUser($registeredPerson->user->id, $request->all());
                $base64 = FileHelper::toResizedBase64($request->file('person_image'), false);

                $dataBinary = base64_decode($base64);

                file_put_contents(
                    public_path('uploads/person_images/' . $getFilename),
                    $dataBinary
                );

                
                $this->vaultSiteService->deleteFaceCard($user->id_card_number);
                $this->vaultSiteService->deleteCard($user->id_card_number);
            }

           return $this->approveRegistered($request, $id);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function delete(Request $request, $id) {
        try {
            $registeredPerson = $this->registerPersonService->getRegisteredPersonById($id);
            $user = $this->userService->getUserById($registeredPerson->user->id);

            $this->vaultSiteService->deleteFaceCard($user->id_card_number);
            $this->vaultSiteService->deleteCard($user->id_card_number);
            $request->merge(['status_level' => 4, 'status' => 'Deleted']);
            $this->registerPersonService->updateStatusRegisteredPersonById($id, $request->all());

            Alert::success('Success', 'Berhasil menghapus registrasi.');
            return redirect()->route('registered.index');
        } catch (\Throwable $th) {
            throw $th;
        }
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
                }else if($row->status_level == 2){
                    if($isEmployee){
                        $approveUrl = route('registered.edit', $row->id);
                        // $deleteUrl = route('registered.delete', $row->id);
                        return '
                                    <a href="'.$approveUrl.'" class="w-8 h-8 bg-warning-100 dark:bg-warning-600/25 text-warning-600 dark:text-warning-400 rounded-full inline-flex items-center justify-center">
                                        <iconify-icon icon="solar:pen-2-outline"></iconify-icon>
                                    </a>';
                    }
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
        
        $expired_date = Carbon::parse($request->expired_at)->setTime(23, 59, 0);
        $formatRequest = FormatRequestVaultsite::formatAddCard($registeredPerson, $areaAccessNumber->access_no,$request->all());
        
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
            $updateData['expired_at'] = $expired_date;
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
