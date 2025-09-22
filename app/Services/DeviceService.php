<?php

namespace App\Services;

use App\Helper\ResponseHelper;
use App\Models\Area;
use App\Models\Device;
use App\Models\DeviceArea;
use Carbon\Carbon;
use Exception;

class DeviceService
{
    public function getAllDevices(array $filters = [], bool $withRelations)
    {
        try {
            if ($withRelations) {
                $query = Device::with('areas'); // eager load relasi areas
            } else {
                $query = Device::query();
            }

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

            $devices = $query->orderBy($orderBy, $order)->get();

            return ResponseHelper::successServiceResponse('Get all devices success', $devices);
        } catch (Exception $e) {
            return ResponseHelper::errorServiceResponse(500, 'Get all devices failed', $e->getMessage());
        }
    }

    public function getDeviceById(int $id, bool $withRelations = false)
    {
        try {
            if ($withRelations) {
                $device = Device::with('areas')->find($id);
            } else {
                $device = Device::find($id);
            }

            if (!$device) {
                return ResponseHelper::errorServiceResponse(404, 'Device not found');
            }

            return ResponseHelper::successServiceResponse('Get device success', $device);
        } catch (Exception $e) {
            return ResponseHelper::errorServiceResponse(500, 'Get device failed', $e->getMessage());
        }
    }

    public function createDevice(array $deviceData)
    {
        try {
            $device = Device::create([
                'device_type' => "Controller",
                'device_name' => isset($deviceData['device_name']) ? $deviceData['device_name'] : null,
            ]);

            return ResponseHelper::successServiceResponse('Create device success', $device);
        } catch (Exception $e) {
            return ResponseHelper::errorServiceResponse(500, 'Create device failed', $e->getMessage());
        }
    }

    public function updateDevice(int $deviceId, array $deviceData)
    {
        try {
            $device = Device::findOrFail($deviceId);

            $device->update([
                'device_type' => "Controller",
                'device_name' => $deviceData['device_name'] ?? $device->device_name,
            ]);

            return ResponseHelper::successServiceResponse('Update device success', $device);
        } catch (Exception $e) {
            return ResponseHelper::errorServiceResponse(500, 'Update device failed', $e->getMessage());
        }
    }

    public function createDeviceArea(int $device_id, array $deviceData)
    {
        try {
            DeviceArea::where('device_id', $device_id)->delete();
            
            $device_areas = [];
            foreach ($deviceData['area_ids'] as $area_id) {
                $device_areas[] = [
                    'device_id' => $device_id,
                    'area_id' => $area_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            $device = DeviceArea::insert($device_areas);

            return ResponseHelper::successServiceResponse('Create device area success', $device);
        } catch (Exception $e) {
            return ResponseHelper::errorServiceResponse(500, 'Create device area failed', $e->getMessage());
        }
    }
}
