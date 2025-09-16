<?php

namespace App\Services;

use App\Helper\ResponseHelper;
use App\Models\Area;
use Exception;

class AreaService
{
    public function createArea(array $areaData)
    {
        try {
            $area = Area::create([
                'access_no' => isset($areaData['access_no']) ? $areaData['access_no'] : null,
                'name' => isset($areaData['name']) ? $areaData['name'] : null,
                'description' => isset($areaData['description']) ? $areaData['description'] : null,
            ]);

            return ResponseHelper::successServiceResponse(200, true, 'Create area success', $area);
        } catch (Exception $e) {
            return ResponseHelper::errorServiceResponse(500, false, 'Create area failed', $e->getMessage());
        }
    }

    public function getNextAvailableAccessNo(): ?string
    {
        // Get all used access numbers
        $usedAccessNos = Area::pluck('access_no')->toArray();

        // Loop from 1 to 999 to find the first available number
        for ($i = 1; $i <= 999; $i++) {
            // Format number as zero-padded 2-digit string
            $formatted = str_pad($i, 2, '0', STR_PAD_LEFT);

            if (!in_array($formatted, $usedAccessNos)) {
                return $formatted;
            }
        }

        // If all numbers are used, return null (or handle as needed)
        return null;
    }

}
