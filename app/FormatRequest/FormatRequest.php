<?php

namespace App\FormatRequest;

class FormatRequest
{
    public static function formatAddArea(string $access_no, array $data)
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
            'AccessNo' => $access_no,
            'Description' => $data['description'] ?? '',
            'TimeZoneGroup' => [
                'entTimeZone' => $entTimeZone
            ]
        ];
    }

}