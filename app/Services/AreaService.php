<?php

namespace App\Services;

use App\Helper\ResponseHelper;
use App\Models\Area;
use Carbon\Carbon;
use App\FormatRequest\FormatRequestVaultsite;
use Exception;

class AreaService
{
    protected $vaultsiteService;

    public function __construct(VaultSiteService $vaultsiteService)
    {
        $this->vaultsiteService = $vaultsiteService;
    }

    public function getAllAreas(array $filters = [])
    {
        try {
            $query = Area::with('childrenArea');

            // Filter search
            if (array_key_exists('search', $filters) && !empty($filters['search'])) {
                $search = trim($filters['search']);
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Filter tanggal
            if (array_key_exists('start_date', $filters) && array_key_exists('end_date', $filters) 
                && !empty($filters['start_date']) && !empty($filters['end_date'])) {
                $query->whereBetween('created_at', [
                    Carbon::parse($filters['start_date'])->startOfDay(),
                    Carbon::parse($filters['end_date'])->endOfDay()
                ]);
            } elseif (array_key_exists('start_date', $filters) && !empty($filters['start_date'])) {
                $query->whereDate('created_at', '>=', Carbon::parse($filters['start_date'])->startOfDay());
            } elseif (array_key_exists('end_date', $filters) && !empty($filters['end_date'])) {
                $query->whereDate('created_at', '<=', Carbon::parse($filters['end_date'])->endOfDay());
            }

            // Order & OrderBy
            $allowedOrderBy = ['id', 'name', 'created_at', 'updated_at'];
            $allowedOrder   = ['asc', 'desc'];

            $orderBy = (array_key_exists('orderby', $filters) && in_array($filters['orderby'], $allowedOrderBy))
                ? $filters['orderby']
                : 'created_at';

            $order = (array_key_exists('order', $filters) && in_array(strtolower($filters['order']), $allowedOrder))
                ? strtolower($filters['order'])
                : 'desc';

            // Ambil hanya root areas (parent_id = null)
            $areas = $query->whereNull('parent_id')
                ->orderBy($orderBy, $order)
                ->get();

            return ResponseHelper::successServiceResponse('Get all areas success', $areas);
        } catch (\Throwable $e) {
            return ResponseHelper::errorServiceResponse(500, 'Get all areas failed', $e->getMessage());
        }
    }

    public function getAreaByID(int $id, bool $withChildren = false)
    {
        try {
            if ($withChildren) {
                $area = Area::with('childrenArea')->find($id);
            } else {
                $area = Area::find($id);
            }

            if (!$area) {
                return ResponseHelper::errorServiceResponse(404, 'Area not found');
            }

            return ResponseHelper::successServiceResponse('Get area success', $area);
        } catch (Exception $e) {
            return ResponseHelper::errorServiceResponse(500, 'Get area failed', $e->getMessage());
        }
    }

    public function createArea(array $areaData)
    {
        try {
            $area = Area::create([
                'access_no' => isset($areaData['access_no']) ? $areaData['access_no'] : null,
                'parent_id' => isset($areaData['parent_id']) ? $areaData['parent_id'] : null,
                'name' => isset($areaData['name']) ? $areaData['name'] : null,
                'description' => isset($areaData['description']) ? $areaData['description'] : null,
            ]);

            return ResponseHelper::successServiceResponse('Create area success', $area);
        } catch (Exception $e) {
            return ResponseHelper::errorServiceResponse(500, 'Create area failed', $e->getMessage());
        }
    }

    public function updateArea(int $id, array $areaData)
    {
        try {
            $area = Area::findOrFail($id);

            // Update hanya field yang diperbolehkan
            $area->update([
                'name'        => isset($areaData['name']) ? $areaData['name'] : $area->name,
                'description' => isset($areaData['description']) ? $areaData['description'] : $area->description,
            ]);

            return ResponseHelper::successServiceResponse('Update area success', $area);
        } catch (Exception $e) {
            return ResponseHelper::errorServiceResponse(500, 'Update area failed', $e->getMessage());
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

    public function processAreas(array $area_ids)
    {
        try {
            // Get all areas
            $areas = Area::get();

            foreach ($areas as $area) {
                $formatted_area = FormatRequestVaultsite::formatAddArea([
                    'access_no' => $area->access_no,
                    'description' => $area->name,
                    'device_id' => $area->devices->pluck('device_name')->toArray()
                ]);

                $storedToVaultsite = $this->vaultsiteService->addArea($formatted_area);
            }

            return ResponseHelper::successServiceResponse('Process areas success', $areas);
        } catch (\Exception $e) {
            return ResponseHelper::errorServiceResponse(500, 'Process areas failed', $e->getMessage());
        }
    }
}
