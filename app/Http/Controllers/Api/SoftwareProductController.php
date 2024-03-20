<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SoftwareProduct;
use App\Models\SoftwareProductLicense;
use App\Models\SoftwareProductPrice;
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
            $validator = Validator::make(['sku' => $sku], ['sku' => 'required|string|min:10|max:10|exists:software_products,sku']);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            $softwareProduct = SoftwareProduct::where('sku', $sku)->first();
            return response()->json(['success' => true, 'software_product' => $softwareProduct]);
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
            $comparator = [];
        
            // Add validation rules for 'name' if present in the request
            if ($request->has('name')) {
                $rules['name'] = 'required|string|min:3|max:100';
                $comparator['name'] = $request->get('name');
            }
            
            // Add validation rules for 'sku' if present in the request
            if ($request->has('sku')) {
                $rules['sku'] = 'required|string|unique:software_products,sku|min:10|max:10';
                $comparator['sku'] = $request->get('sku');
            } else {
                $rules['sku'] = 'required|string|exists:software_products,sku|min:10|max:10';
                $comparator['sku'] = $sku;
            }
            
            $validator = Validator::make($comparator, $rules);

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
            $validator = Validator::make(['sku' => $sku], ['sku' => 'required|string|min:10|max:10|exists:software_products,sku']);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            SoftwareProductLicense::where('software_product_sku', $sku)->delete();
            SoftwareProduct::where('sku', $sku)->delete();
            return response()->json(['success' => true, 'message' => 'Software product deleted successfully. All the corresponding software licenses where deleted as well']);
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
    public function softwareProductLicenses(String $sku): JsonResponse
    {
        try {
            $validator = Validator::make(['sku' => $sku], ['sku' => 'required|string|min:10|max:10|exists:software_products,sku']);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            $licenses = SoftwareProductLicense::where('software_product_sku', $sku)->get();
            return response()->json(['success' => true, 'licenses' => $licenses, 'message' => 'Retrieved all software product licenses for the product with sku: ' . $sku]);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param String $sku
     * @param String $os_slug
     * @return JsonResponse
     */
    public function softwareProductLicensesOS(String $sku, String $os_slug): JsonResponse
    {
        try {
            $validator = Validator::make(
                [
                    'sku' => $sku,
                    'os_slug' => $os_slug
                ], [
                    'sku' => 'required|string|min:10|max:10|exists:software_products,sku',
                    'os_slug' => 'required|string|min:3|max:100|exists:operative_systems,slug'
                ]);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            $licenses = SoftwareProductLicense::where('software_product_sku', $sku)->where('operative_system_slug', $os_slug)->get();
            return response()->json(['success' => true, 'licenses' => $licenses, 'message' => 'Retrieved all software product licenses for the product with sku: ' . $sku . ' and operative system with slug: ' .$os_slug . ' retrieved successfully.']);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param String $os_slug
     * @return JsonResponse
     */
    public function operativeSystemSofwareProductLicenses(String $os_slug): JsonResponse
    {
        try {
            $validator = Validator::make(
                [
                    'os_slug' => $os_slug
                ], [
                    'os_slug' => 'required|string|min:3|max:100|exists:operative_systems,slug'
                ]);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            $licenses = SoftwareProductLicense::where('operative_system_slug', $os_slug)->get();
            return response()->json(['success' => true, 'licenses' => $licenses, 'message' => 'Retrieved all software product licenses for the product with operative system with slug: ' .$os_slug . ' retrieved successfully.']);
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
    public function softwareProductLicenseStoreOS(String $sku, String $os_slug): JsonResponse
    {
        try {
            $validator = Validator::make([
                'sku' => $sku,
                'os_slug' => $os_slug
            ], [
                'sku' => 'required|string|min:10|max:10|exists:software_products,sku',
                'os_slug' => 'required|string|min:3|max:100|exists:operative_systems,slug',
            ]);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
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
     * @param Request $request
     * @param String $sku
     * @return JsonResponse
     */
    public function softwareProductLicenseStore(Request $request, String $sku): JsonResponse
    {
        try {
            $validator = Validator::make([
                'sku' => $sku,
                'os_slug' => $request->get('os_slug')
            ], [
                'sku' => 'required|string|min:10|max:10|exists:softwre_products,sku',
                'os_slug' => 'required|string|min:3|max:100|exists:operative_systems,slug',
            ]);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            $license = new softwareProductLicense();
            $license->software_product_sku = $sku;
            $license->operative_system_slug = $request->get('os_slug');
            $licenses_count = SoftwareProductLicense::count();
            $license->serial = $license->generateSerialNumber($sku, $request->get('os_slug'), $licenses_count);
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
     * @param String $os_slug
     * @return JsonResponse
     */
    public function operativeSystemsoftwareProductLicenseStore(Request $request, String $os_slug): JsonResponse
    {
        try {
            $validator = Validator::make([
                'sku' => $request->get('sku'),
                'os_slug' => $os_slug
            ], [
                'sku' => 'required|string|min:10|max:10|exists:software_products,sku',
                'os_slug' => 'required|string|min:3|max:100|exists:operative_systems,slug',
            ]);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            $license = new softwareProductLicense();
            $license->software_product_sku = $request->get('sku');
            $license->operative_system_slug = $os_slug;
            $licenses_count = SoftwareProductLicense::count();
            $license->serial = $license->generateSerialNumber($request->get('sku'), $os_slug, $licenses_count);
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
    public function generalSoftwareProductLicenseStore(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'os_slug' => 'required|string|min:3|max:100|exists:operative_systems,slug',
                'sku' => 'required|string|min:10|max:10|exists:software_products,sku',
                'serial' => 'exclude',
                // Add other validation rules as needed
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
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
    public function softwareProductLicenseShow(String $serial): JsonResponse
    {
        try {
            $validator = Validator::make([
                'serial' => $serial
            ], [
                'serial' => 'required|string|min:100|max:100|exists:software_product_licenses,serial',
            ]);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            $license = SoftwareProductLicense::where('serial', $serial)->first();
            return response()->json(['success' => true, 'license' => $license]);
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
                'serial' => 'required|string|min:100|max:100|exists:software_product_licenses,serial'
            ];
            $comparator = [
                'serial' => $serial
            ];
        
            // Add validation rules for 'name' if present in the request
            if ($request->has('software_product_sku')) {
                $rules['software_product_sku'] = 'required|string|min:10|max:10|exists:software_products,sku';
                $comparator['software_product_sku'] = $request->get('software_product_sku');
            }
            
            // Add validation rules for 'sku' if present in the request
            if ($request->has('operative_system_slug')) {
                $rules['operative_system_slug'] = 'required|string|min:3|max:100|exists:operative_systems,slug';
                $comparator['operative_system_slug'] = $request->get('operative_system_slug');
            }
            $validator = Validator::make($comparator, $rules);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            SoftwareProductLicense::where('serial', $serial)->update($validator->validated());
            $message = 'Software Product License Updated. ';
            if ($request->has('serial')) {
                $message .= ' Serial number is not updated since is generated from the platform';
            }
            return response()->json(['success' => true, 'data' => $validator->validated(), 'message' => $message], 200);  
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
            $validator = Validator::make(['serial' => $serial], ['serial' => 'required|string|min:100|max:100|exists:software_product_licenses,serial']);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            SoftwareProductLicense::where('serial', $serial)->delete();
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

    /**
    * Display a listing of the resource
    * @param String $sku
    * @param String $os_slug
    * @return JsonResponse
    */
    public function softwareProductPricesOS(String $sku, String $os_slug): JsonResponse
    {
        try {
            $validator = Validator::make([
                'sku' => $sku,
                'os_slug' => $os_slug
            ], [
                'sku' => 'required|string|min:10|max:10|exists:software_products,sku',
                'os_slug' => 'required|string|min:3|max:100|exists:operative_systems,slug',
            ]);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            $prices = SoftwareProductPrice::where('software_product_sku', $sku)->where('operative_system_slug', $os_slug)->get();
            return response()->json(['success' => true, 'prices' => $prices, 'message' => 'All Software product prices  for software product with SKU: ' . $sku . ' and operative system slug: ' . $os_slug . ' retrieved successfully']);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }

    /**
    * Display a listing of the resource
    * @param String $sku
    * @return JsonResponse
    */
    public function softwareProductPrices(String $sku): JsonResponse
    {
        try {
            $validator = Validator::make([
                'sku' => $sku,
            ], [
                'sku' => 'required|string|min:10|max:10|exists:software_products,sku',
            ]);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            $prices = SoftwareProductPrice::where('software_product_sku', $sku)->get();
            return response()->json(['success' => true, 'prices' => $prices, 'message' => 'All Software product prices  for software product with SKU: ' . $sku . ' retrieved successfully']);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }

    /**
    * Display a listing of the resource
    * @param String $os_slug
    * @return JsonResponse
    */
    public function operativeSystemSoftwareProductPrices(String $os_slug): JsonResponse
    {
        try {
            $validator = Validator::make([
                'os_slug' => $os_slug,
            ], [
                'os_slug' => 'required|string|min:3|max:1000|exists:operative_systems,slug',
            ]);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            $prices = SoftwareProductPrice::where('operative_system_slug', $os_slug)->get();
            return response()->json(['success' => true, 'prices' => $prices, 'message' => 'All Software product prices  for operative system slug: ' . $os_slug . ' retrieved successfully']);
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
    public function generalSoftwareProductPriceStore(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'value' => 'required|numeric|min:0',
                'software_product_sku' => 'required|string|exists:software_products,sku',
                'operative_system_slug' => 'required|string|exists:operative_systems,slug',
                // Add other validation rules as needed
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $price = SoftwareProductPrice::create($validator->validated());
            return response()->json(['success' => true, 'price' => $price], 201);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param Request $request
    * @param String $sku
    * @param String $os_slug
    * @return JsonResponse
    */
    public function SoftwareProductPriceStoreOS(Request $request, String $sku, String $os_slug): JsonResponse
    {
        try {
            $validator = Validator::make([
                'value' => $request->get('value'),
                'software_product_sku' => $sku,
                'operative_system_slug' => $os_slug
            ], [
                'value' => 'required|numeric|min:0',
                'software_product_sku' => 'required|string|exists:software_products,sku',
                'operative_system_slug' => 'required|string|exists:operative_systems,slug',
                // Add other validation rules as needed
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $price = SoftwareProductPrice::create($validator->validated());
            return response()->json(['success' => true, 'price' => $price], 201);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param Request $request
    * @param String $sku
    * @return JsonResponse
    */
    public function softwareProductPriceStore(Request $request, String $sku): JsonResponse
    {
        try {
            $validator = Validator::make([
                'value' => $request->get('value'),
                'software_product_sku' => $sku,
                'operative_system_slug' => $request->get('os_slug')
            ], [
                'value' => 'required|numeric|min:0',
                'software_product_sku' => 'required|string|exists:software_products,sku',
                'operative_system_slug' => 'required|string|exists:operative_systems,slug',
                // Add other validation rules as needed
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $price = SoftwareProductPrice::create($validator->validated());
            return response()->json(['success' => true, 'prices' => $price], 201);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param Request $request
    * @param String $os_slug
    * @return JsonResponse
    */
    public function operativeSystemSoftwareProductPriceStore(Request $request, String $os_slug): JsonResponse
    {
        try {
            $validator = Validator::make([
                'value' => $request->get('value'),
                'software_product_sku' => $request->get('sku'),
                'operative_system_slug' => $os_slug
            ], [
                'value' => 'required|numeric|min:0',
                'software_product_sku' => 'required|string|exists:software_products,sku',
                'operative_system_slug' => 'required|string|exists:operative_systems,slug',
                // Add other validation rules as needed
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $price = SoftwareProductPrice::create($validator->validated());
            return response()->json(['success' => true, 'prices' => $price], 201);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function softwareProductPriceShow(int $id): JsonResponse
    {
        try {
            $validator = Validator::make([
                'id' => $id,
            ], [
                'id' => 'required|integer|min:1|exists:software_product_prices,id',
                // Add other validation rules as needed
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            $price = SoftwareProductPrice::where('id', $id)->first();
            return response()->json(['success' => true, 'price' => $price]);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function softwareProductPriceUpdate(Request $request, int $id): JsonResponse
    {
        try {
            if (!$request->hasAny(['software_product_sku', 'operative_system_slug', 'value'])) {
                return response()->json(['success' => false, 'message' => 'No data to update'], 422);
            }
            $rules = [
                'id' => 'required|integer|min:1|exists:software_product_prices,id'
            ];
            $comparator = [
                'id' => $id
            ];
        
            // Add validation rules for 'software_product_sku' if present in the request
            if ($request->has('software_product_sku')) {
                $rules['software_product_sku'] = 'required|string|min:10|max:10|exists:software_products,sku';
                $comparator['software_product_sku'] = $request->get('software_product_sku');
            }
            
            // Add validation rules for 'operative_system_slug' if present in the request
            if ($request->has('operative_system_slug')) {
                $rules['operative_system_slug'] = 'required|string|min:3|max:100|exists:operative_systems,slug';
                $comparator['operative_system_slug'] = $request->get('operative_system_slug');
            }
            $validator = Validator::make($comparator, $rules);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            softwareProductPrice::where('id', $id)->update($validator->validated());
            return response()->json(['success' => true, 'data' => $validator->validated(), 'message' => 'Software Product License Updated.'], 200);  
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroySoftwareProductPrice(int $id): JsonResponse
    {
        try {
            $validator = Validator::make(['id' => $id], ['serial' => 'required|integer|min:1|exists:software_product_prices,id']);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            SoftwareProductPrice::where('id', $id)->delete();
            return response()->json(['success' => true, 'message' => 'License deleted successfully']);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }
}
