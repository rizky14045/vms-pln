<?php

namespace App\FormatRequest;

use App\Helper\FileHelper;

class FormatRequestVaultsite
{
    public static function formatAddArea(array $data)
    {
        $entTimeZone = [];

        if (!empty($data['device_id']) && is_array($data['device_id'])) {
           
            foreach ($data['device_id'] as $device) {
                $entTimeZone[] = [
                    'DoorName' => $device,
                    'TimeZone' => '01'
                ];
            }
        }

        return [
            'AccessNo' => $data['access_no'],
            'Description' => $data['description'] ?? '',
            'TimeZoneGroup' => [
                'entTimeZone' => $entTimeZone
            ]
        ];
    }

    public static function formatAddCard($data)
    {
        return [
            "CardNo" => "20211116", // Dinamis
            "Name" => $data->user->name ?? "", // data user
            "CardPinNo" => "0000", // default
            "CardType" => "Normal", // default
            "Department" => "",
            "Company" => "PLN Nusantara Power",
            "Gentle" => "",
            "AccessLevel" => "02", // *
            "FaceAccessLevel" => "00", // default
            "LiftAccessLevel" => "01", // default
            "BypassAP" => false, // default
            "ActiveStatus" => true, // default
            "NonExpired" => true, // default
            "ExpiredDate" => "2020/12/31", // 5 tahun kedepan
            "VehicleNo" => "",
            "FloorNo" => "",
            "UnitNo" => "",
            "ParkingNo" => "",
            "StaffNo" => $data->user->nid ?? "", 
            "Title" => "",
            "Position" => "",
            "NRIC" => "",
            "Passport" => "",
            "Race" => "",
            "DOB" => "",
            "JoiningDate" => "2020/01/01", //tanggal registrasi
            "ResignDate" => "",
            "Address1" => "",
            "Address2" => "",
            "PostalCode" => "",
            "City" => "",
            "State" => "",
            "Email" => "string",
            "MobileNo" => "string",
            "Photo" => FileHelper::base64Encode(public_path('uploads/person_images/'.$data->person_image)),
            "DownloadCard" => true,
        ];

    }

}