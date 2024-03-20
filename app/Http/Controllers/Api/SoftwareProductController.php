<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SoftwareProduct;
use App\Models\SoftwareProductLicense;
use App\Models\OperativeSystem;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SoftwareProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $softwareProducts = SoftwareProduct::all();
            return response()->json(['success' => true, 'software_products' => $softwareProducts]);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|min:3|max:100',
                'sku' => 'required|string|unique:software_products,sku|min:10|max:10',
                // Add other validation rules as needed
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $softwareProduct = SoftwareProduct::create($validator->validated());
            return response()->json(['success' => true, 'software_product' => $softwareProduct], 201);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param String $sku
     * @return JsonResponse
     */
    public function show(String $sku): JsonResponse
    {
        try {
            $validator = Validator::make(['sku' => $sku], ['sku' => 'required|string|min:10|max:10']);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            $softwareProduct = SoftwareProduct::where('sku', $sku)->first();
            if ($softwareProduct) {
                return response()->json(['success' => true, 'software_product' => $softwareProduct]);
            } else {
                return response()->json(['success' => false, 'message' => 'Software product not found'], 404);
            }
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param String $sku
     * @return JsonResponse
     */
    public function update(Request $request, String $sku): JsonResponse
    {
        try {
            // Check if the request has any data to update
            if (!$request->hasAny(['name', 'sku'])) {
                return response()->json(['success' => false, 'message' => 'No data to update'], 422);
            }

            $rules = [];
        
            // Add validation rules for 'name' if present in the request
            if ($request->has('name')) {
                $rules['name'] = 'required|string|min:3|max:100';
            }
            
            // Add validation rules for 'sku' if present in the request
            if ($request->has('sku')) {
                $rules['sku'] = 'required|string|unique:software_products,sku|min:10|max:10';
            }
            
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            $softwareProduct = SoftwareProduct::where('sku', $sku)->first();
            if ($softwareProduct) {
                SoftwareProduct::where('sku', $sku)->update($validator->validated());
                return response()->json(['success' => true, 'data' => $validator->validated(), 'message' => 'Software Product Updated'], 200);
            } else {
                return response()->json(['success' => false, 'message' => 'Software product not found'], 404);
            }
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param String $sku
     * @return JsonResponse
     */
    public function destroy(String $sku): JsonResponse
    {
        try {
            $validator = Validator::make(['sku' => $sku], ['sku' => 'required|string|min:10|max:10']);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            $softwareProduct = SoftwareProduct::where('sku', $sku)->first();
            if ($softwareProduct) {
                SoftwareProductLicense::where('software_product_sku', $sku)->delete();
                SoftwareProduct::where('sku', $sku)->delete();
                return response()->json(['success' => true, 'message' => 'Software product deleted successfully. All the corresponding software licenses where deleted as well']);
            } else {
                return response()->json(['success' => false, 'message' => 'Software product not found'], 404);
            }
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param String $price
     * @return JsonResponse
     */
    public function softwareProductLicenses(String $sku): JsonResponse
    {
        try {
            $validator = Validator::make(['sku' => $sku], ['sku' => 'required|string|min:10|max:10']);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            $softwareProduct = SoftwareProduct::where('sku', $sku)->first();
            if ($softwareProduct) {
                $licenses = SoftwareProductLicense::where('software_product_sku', $sku)->get();
                return response()->json(['success' => true, 'licenses' => $licenses, 'message' => 'Retrieved all software product licenses for the product with sku: ' . $sku]);
            } else {
                return response()->json(['success' => false, 'message' => 'Software product not found'], 404);
            }
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @return JsonResponse
     */
    public function generalSoftwareProductLicenses(): JsonResponse
    {
        try {
            $licenses = SoftwareProductLicense::all();
            return response()->json(['success' => true, 'licenses' => $licenses, 'message' => 'Retrieved all software product licenses ' ], 200);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param String $sku
     * @param String $os_slug
     * @return JsonResponse
     */
    public function softwareProductLicensesStore(String $sku, String $os_slug): JsonResponse
    {
        try {
            $validator = Validator::make([
                'sku' => $sku,
                'os_slug' => $os_slug
            ], [
                'sku' => 'required|string|min:10|max:10',
                'os_slug' => 'required|string|max:100',
            ]);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            $softwareProduct = SoftwareProduct::where('sku', $sku)->first();
            if (!$softwareProduct) {
                return response()->json(['success' => false, 'message' => 'Software product not found'], 404);
            }
            $operative_system = OperativeSystem::where('slug', $os_slug)->first();
            if (!$operative_system) {
                return response()->json(['success' => false, 'message' => 'Operative System not found'], 404);
            }
            $license = new softwareProductLicense();
            $license->software_product_sku = $sku;
            $license->operative_system_slug = $os_slug;
            $licenses_count = SoftwareProductLicense::count();
            $license->serial = $license->generateSerialNumber($sku, $os_slug, $licenses_count);
            $license->save();
            return response()->json(['success' => true, 'data' => $license], 201);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function generalSoftwareProductLicensesStore(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'os_slug' => 'required|string|max:100',
                'sku' => 'required|string|min:10|max:10',
                'serial' => 'exclude',
                // Add other validation rules as needed
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            $softwareProduct = SoftwareProduct::where('sku', $validator->validated()['sku'])->first();
            if (!$softwareProduct) {
                return response()->json(['success' => false, 'message' => 'Software product not found'], 404);
            }
            $operative_system = OperativeSystem::where('slug', $validator->validated()['os_slug'])->first();
            if (!$operative_system) {
                return response()->json(['success' => false, 'message' => 'Operative System not found'], 404);
            }
            $license = new softwareProductLicense();
            $license->software_product_sku = $validator->validated()['sku'];
            $license->operative_system_slug = $validator->validated()['os_slug'];
            $licenses_count = SoftwareProductLicense::count();
            $license->serial = $license->generateSerialNumber($validator->validated()['sku'], $validator->validated()['os_slug'], $licenses_count);
            $license->save();
            return response()->json(['success' => true, 'data' => $license], 201);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param String $serial
     * @return JsonResponse
     */
    public function softwareProductLicensesShow(String $serial): JsonResponse
    {
        try {
            $validator = Validator::make([
                'serial' => $serial
            ], [
                'serial' => 'required|string|min:100|max:100',
            ]);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            $license = SoftwareProductLicense::where('serial', $serial)->first();
            if ($license) {
                return response()->json(['success' => true, 'license' => $license]);
            } else {
                return response()->json(['success' => false, 'message' => 'Software product license with serial: ' . $serial . ' not found'], 404);
            }
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param String $serial
     * @return JsonResponse
     */
    public function softwareProductLicenseUpdate(Request $request, String $serial): JsonResponse
    {
        try {
            if (!$request->hasAny(['software_product_sku', 'operative_system_slug'])) {
                return response()->json(['success' => false, 'message' => 'No data to update'], 422);
            }
            $rules = [
                'serial' => $serial
            ];
        
            // Add validation rules for 'name' if present in the request
            if ($request->has('software_product_sku')) {
                $rules['software_product_sku'] = 'required|string|min:10|max:10';
            }
            
            // Add validation rules for 'sku' if present in the request
            if ($request->has('operative_system_slug')) {
                $rules['operative_system_slug'] = 'required|string|max:100';
            }
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            $license = SoftwareProductLicense::where('serial', $serial)->first();
            if ($license) {
                softwareProductLicense::where('serial', $serial)->update($validator->validated());
                $message = 'Software Product License Updated. ';
                if ($request->has('serial')) {
                    $message .= ' Serial number is not updated since is generated from the platform';
                }
                return response()->json(['success' => true, 'data' => $validator->validated(), 'message' => $message], 200);  
            } else {
                return response()->json(['success' => false, 'message' => 'Software product license not found'], 404);
            }
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param String $serial
     * @return JsonResponse
     */
    public function destroySoftwareProductLicense(String $serial): JsonResponse
    {
        try {
            $validator = Validator::make(['serial' => $serial], ['serial' => 'required|string|min:100|max:100']);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            $license = softwareProductLicense::where('serial', $serial)->first();
            if ($license) {
                softwareProductLicense::where('serial', $serial)->delete();
            } else {
                return response()->json(['success' => false, 'message' => 'Software product license not found'], 404);
            }
            return response()->json(['success' => true, 'message' => 'License deleted successfully']);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }

    /**
    * Display a listing of the resource.
    *
    * @return JsonResponse
    */
    public function generalSoftwareProductPrices(): JsonResponse
    {
        try {
            $prices = SoftwareProductPrice::all();
            return response()->json(['success' => true, 'prices' => $prices, 'message' => 'All Software product prices retrieved successfully']);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }
}
