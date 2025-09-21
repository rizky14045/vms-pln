<?php

namespace App\Services;

use App\Helper\ResponseHelper;
use App\Models\Area;
use App\Models\Device;
use Carbon\Carbon;
use Exception;

class DeviceService
{
    public function getAllDevices(array $filters = [])
    {
        try {
            $query = Device::query();

            // Filter search
            if (!empty($filters['search'])) {
                $search = $filters['search'];
                $query->where(function ($q) use ($search) {
                    $q->where('device_name', 'like', "%{$search}%");
                });
            }

            // Filter tanggal
            if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
                $query->whereBetween('created_at', [
                    Carbon::parse($filters['start_date'])->startOfDay(),
                    Carbon::parse($filters['end_date'])->endOfDay()
                ]);
            } elseif (!empty($filters['start_date'])) {
                $query->whereDate('created_at', '>=', Carbon::parse($filters['start_date'])->startOfDay());
            } elseif (!empty($filters['end_date'])) {
                $query->whereDate('created_at', '<=', Carbon::parse($filters['end_date'])->endOfDay());
            }

            // Order & OrderBy
            $order   = $filters['order']   ?? 'desc';
            $orderBy = $filters['orderby'] ?? 'created_at';

            $areas = $query->orderBy($orderBy, $order)->get();

            return ResponseHelper::successServiceResponse('Get all devices success', $areas);
        } catch (Exception $e) {
            return ResponseHelper::errorServiceResponse(500, 'Get all devices failed', $e->getMessage());
        }
    }

    public function createArea(array $areaData)
    {
        try {
            $area = Area::create([
                'access_no' => isset($areaData['access_no']) ? $areaData['access_no'] : null,
                'name' => isset($areaData['name']) ? $areaData['name'] : null,
                'description' => isset($areaData['description']) ? $areaData['description'] : null,
            ]);

            return ResponseHelper::successServiceResponse('Create area success', $area);
        } catch (Exception $e) {
            return ResponseHelper::errorServiceResponse(500, 'Create area failed', $e->getMessage());
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
