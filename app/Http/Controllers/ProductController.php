<?php

namespace App\Http\Controllers;

use App\Exports\ProductsExport;
use App\Services\ProductService;
use App\Validation\ProductValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;

        // Halaman product hanya bisa diakses oleh role super-admin
        $this->middleware('role:super-admin')->only(['index', 'create', 'store', 'edit', 'update', 'export']);
    }

    protected function validator(array $data, $validation, array $messages = [])
    {
        return Validator::make($data, $validation, $messages);
    }

    public function index(Request $request)
    {
        // Get filter from request
        $filters = [
            'start_date' => $request->input('start_date'),
            'end_date'   => $request->input('end_date'),
            'search'     => $request->input('search'),
            'order'      => $request->input('order', 'desc'),
            'orderby'    => $request->input('orderby', 'created_at'),
        ];

        // Call service to get products with filters
        $productsResponse = $this->productService->getAllProducts($filters);

        if (!$productsResponse['status']) {
            return redirect('login')->withErrors($productsResponse['message']);
        }

        $products = $productsResponse['data'] ?? [];

        return view('pages.products.index', [
            'products' => $products,
            'filters'  => $filters,
        ]);
    }

    public function export(Request $request)
    {
        // Pakai filter yang sama dengan index, supaya export mengikuti pencarian/rentang tanggal yang sedang dipakai
        $filters = [
            'start_date' => $request->input('start_date'),
            'end_date'   => $request->input('end_date'),
            'search'     => $request->input('search'),
            'order'      => $request->input('order', 'desc'),
            'orderby'    => $request->input('orderby', 'created_at'),
        ];

        $productsResponse = $this->productService->getAllProducts($filters);

        if (!$productsResponse['status']) {
            return redirect()->back()->withErrors($productsResponse['message']);
        }

        $fileName = 'products-' . now()->format('Y-m-d_His') . '.xlsx';

        return Excel::download(new ProductsExport($productsResponse['data']), $fileName);
    }

    public function create(): View
    {
        $typesResponse = $this->productService->getAllProductTypes();
        if (!$typesResponse['status']) {
            return redirect()->back()->withErrors($typesResponse['message']);
        }

        return view('pages.products.create', [
            'productTypes' => $typesResponse['data'],
        ]);
    }

    public function store(Request $request)
    {
        // Validate request data
        $validator = $this->validator($request->all(), ProductValidation::rulesForCreate(), ProductValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        $stored_product = $this->productService->createProduct($request->all());

        if (!$stored_product['status']) {
            DB::rollBack();
            return redirect()->back()->withErrors($stored_product['message'])->withInput();
        }

        DB::commit();

        return redirect()->route('products.index')->with('success', 'Product berhasil dibuat.');
    }

    public function edit($id)
    {
        $productResponse = $this->productService->getProductById($id);
        if (!$productResponse['status']) {
            return redirect()->route('products.index')->withErrors($productResponse['message']);
        }

        $typesResponse = $this->productService->getAllProductTypes();
        if (!$typesResponse['status']) {
            return redirect()->back()->withErrors($typesResponse['message']);
        }

        return view('pages.products.edit', [
            'product'      => $productResponse['data'],
            'productTypes' => $typesResponse['data'],
        ]);
    }

    public function update(Request $request, $id)
    {
        // Validate request data
        $validator = $this->validator($request->all(), ProductValidation::rulesForUpdate(), ProductValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        $stored_product = $this->productService->updateProduct($id, $request->all());

        if (!$stored_product['status']) {
            DB::rollBack();
            return redirect()->back()->withErrors($stored_product['message'])->withInput();
        }

        DB::commit();

        return redirect()->route('products.index')->with('success', 'Product berhasil diedit.');
    }
}
