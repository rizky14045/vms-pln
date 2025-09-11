<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Service\UserService;
use App\Services\VaultSiteService;
use Illuminate\Http\Request;

class AuthenticationController extends Controller
{
    protected $vaultSiteService;
    public function __construct(
        VaultSiteService $vaultSiteService
    ) {
        $this->vaultSiteService = $vaultSiteService;
    }
    
    public function viewLogin(): View {
        return view('login');
    }
    public function viewRegister(): View {
        return view('register');
    }

    public function addData(){
         $data = [
            "CardNo" => "20211114",
            "Name" => "Test API 211114",
            "CardPinNo" => "0000",
            "CardType" => "Normal",
            "Department" => "RnD",
            "Company" => "binRusdi",
            "Gentle" => "Male",
            "AccessLevel" => "02",
            "FaceAccessLevel" => "00",
            "LiftAccessLevel" => "01",
            "BypassAP" => false,
            "ActiveStatus" => true,
            "NonExpired" => true,
            "ExpiredDate" => "2020/12/31",
            "VehicleNo" => "B6694UWA",
            "FloorNo" => "3",
            "UnitNo" => "25",
            "ParkingNo" => "4",
            "StaffNo" => "12514",
            "Title" => "string",
            "Position" => "string",
            "NRIC" => "string",
            "Passport" => "string",
            "Race" => "string",
            "DOB" => "1985/07/10",
            "JoiningDate" => "2020/01/01",
            "ResignDate" => "2020/12/31",
            "Address1" => "string",
            "Address2" => "string",
            "PostalCode" => "string",
            "City" => "string",
            "State" => "string",
            "Email" => "string",
            "MobileNo" => "string",
            "Photo" => "string",
            "DownloadCard" => true,
        ];

        $response = $this->vaultSiteService->addCard($data);

        return response()->json($response);
    }
}
