<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use App\Validation\ChangePasswordValidation;

class MyProfileController extends Controller
{

    public function __construct(
        protected UserService $userService
    ) {}

    public function index(): View {
        return view('my-profile');
    }

    public function changePassword(Request $request) {
        
       $validator = Validator::make($request->all(), ChangePasswordValidation::rulesForChange(), ChangePasswordValidation::messages());
        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = $this->userService->getUserById(Auth::user()->id);
        if( Hash::check($request->old_password,$user->password) ){
            $user->password = bcrypt($request->new_password);
            $user->save();

            Alert::success('Berhasil', 'Berhasil merubah password!');
            return redirect()->back();
        } else {
            Alert::error('Gagal', 'Gagal merubah password!');
            return redirect()->back();
        }
        
    }
}
