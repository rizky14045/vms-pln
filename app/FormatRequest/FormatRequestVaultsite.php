<?php

namespace App\FormatRequest;

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

}