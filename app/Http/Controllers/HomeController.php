<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Helper\FileHelper;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;
use App\Services\RegisterPersonService;
use Illuminate\Support\Facades\Validator;
use App\Validation\RegisterRequestValidation;

class HomeController extends Controller
{
    public function __construct(
        protected UserService $userService,
        protected RegisterPersonService $registerPersonService
    ) {}

    public function index(): View {
        return view('home');
    }
    public function registerVisitor(): View {
        return view('register-visitor');
    }

    public function registerRequest(Request $request) {

        try {
            
            DB::beginTransaction();

            $validator = Validator::make($request->all(), RegisterRequestValidation::rulesForCreate(), RegisterRequestValidation::messages());
            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            //check if user exist by nid
            $user = $this->userService->getUserByNid($request->nid);
            if(!$user) {
                //create user
                $user = $this->userService->createUser($request->all());
            }

            $getFilename = FileHelper::generatedFileName('Person', $request->person_image->extension());
            $request->merge(['image_name' => $getFilename,'user_id' => $user->id]);
            
            $createdRegister = $this->registerPersonService->createRegisteredPerson($request->all());

            FileHelper::saveFile($request->person_image, 'uploads/person_images', $getFilename);
            DB::commit();
            return redirect()->route('register-visitor')->with('success', 'Berhasil melakukan registrasi kunjungan, silahkan tunggu persetujuan dari petugas.');

        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
       

    }
}
