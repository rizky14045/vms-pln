<?php

namespace App\Services;

use App\Helper\ResponseHelper;
use App\Models\Area;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AreaService
{
    public function createArea(array $areaData)
    {
        try {
            $area = Area::create([
                'access_no' => isset($areaData['access_no']) ? $areaData['access_no'] : null,
                'description' => isset($areaData['description']) ? $areaData['description'] : null,
            ]);

            return ResponseHelper::successServiceResponse(200, true, 'Create area success', $area);
        } catch (Exception $e) {
            return ResponseHelper::errorServiceResponse(500, false, 'Create area failed', $e->getMessage());
        }
    }
}
