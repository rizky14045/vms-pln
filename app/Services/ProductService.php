<?php

namespace App\Services;

use App\Helper\ResponseHelper;
use App\Models\Product;
use App\Models\ProductType;
use Carbon\Carbon;
use Exception;

class ProductService
{
    public function getAllProducts(array $filters = [])
    {
        try {
            $query = Product::with('productType');

            // Filter search
            if (!empty($filters['search'])) {
                $search = trim($filters['search']);
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
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
            $allowedOrderBy = ['id', 'name', 'price', 'created_at', 'updated_at'];
            $allowedOrder   = ['asc', 'desc'];

            $orderBy = (!empty($filters['orderby']) && in_array($filters['orderby'], $allowedOrderBy))
                ? $filters['orderby']
                : 'created_at';

            $order = (!empty($filters['order']) && in_array(strtolower($filters['order']), $allowedOrder))
                ? strtolower($filters['order'])
                : 'desc';

            $products = $query->orderBy($orderBy, $order)->get();

            return ResponseHelper::successServiceResponse('Get all products success', $products);
        } catch (Exception $e) {
            return ResponseHelper::errorServiceResponse(500, 'Get all products failed', $e->getMessage());
        }
    }

    public function getProductById(int $id)
    {
        try {
            $product = Product::with('productType')->find($id);

            if (!$product) {
                return ResponseHelper::errorServiceResponse(404, 'Product not found');
            }

            return ResponseHelper::successServiceResponse('Get product success', $product);
        } catch (Exception $e) {
            return ResponseHelper::errorServiceResponse(500, 'Get product failed', $e->getMessage());
        }
    }

    public function getAllProductTypes()
    {
        try {
            $types = ProductType::orderBy('name')->get();

            return ResponseHelper::successServiceResponse('Get all product types success', $types);
        } catch (Exception $e) {
            return ResponseHelper::errorServiceResponse(500, 'Get all product types failed', $e->getMessage());
        }
    }

    public function createProduct(array $productData)
    {
        try {
            $productTypeId = $this->resolveProductTypeId($productData);

            if ($productTypeId === null) {
                return ResponseHelper::errorServiceResponse(422, 'Tipe produk tidak valid');
            }

            $product = Product::create([
                'name'            => $productData['name'] ?? null,
                'description'     => $productData['description'] ?? null,
                'price'           => $productData['price'] ?? null,
                'product_type_id' => $productTypeId,
            ]);

            return ResponseHelper::successServiceResponse('Create product success', $product);
        } catch (Exception $e) {
            return ResponseHelper::errorServiceResponse(500, 'Create product failed', $e->getMessage());
        }
    }

    public function updateProduct(int $id, array $productData)
    {
        try {
            $product = Product::findOrFail($id);

            $productTypeId = $this->resolveProductTypeId($productData);

            if ($productTypeId === null) {
                return ResponseHelper::errorServiceResponse(422, 'Tipe produk tidak valid');
            }

            $product->update([
                'name'            => $productData['name'] ?? $product->name,
                'description'     => $productData['description'] ?? $product->description,
                'price'           => $productData['price'] ?? $product->price,
                'product_type_id' => $productTypeId,
            ]);

            return ResponseHelper::successServiceResponse('Update product success', $product);
        } catch (Exception $e) {
            return ResponseHelper::errorServiceResponse(500, 'Update product failed', $e->getMessage());
        }
    }

    /**
     * Resolve product_type_id dari input. Jika user memilih tipe yang sudah ada,
     * pakai product_type_id. Jika user mengetik tipe baru (new_product_type),
     * buat record ProductType baru (atau pakai yang sudah ada dengan nama sama).
     */
    protected function resolveProductTypeId(array $productData): ?int
    {
        if (!empty($productData['new_product_type'])) {
            $type = ProductType::firstOrCreate([
                'name' => trim($productData['new_product_type']),
            ]);

            return $type->id;
        }

        if (!empty($productData['product_type_id'])) {
            return (int) $productData['product_type_id'];
        }

        return null;
    }
}
