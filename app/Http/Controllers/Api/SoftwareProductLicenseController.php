<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SoftwareProductLicense;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SoftwareProductLicenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $licenses = SoftwareProductLicense::all();
            return response()->json(['success' => true, 'data' => $licenses]);
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
                'name' => 'required|string|max:255',
                'software_product_sku' => 'required|string|exists:software_products,sku',
                'operative_system_slug' => 'required|string|exists:operative_systems,slug',
                // Add other validation rules as needed
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $license = SoftwareProductLicense::create($validator->validated());
            return response()->json(['success' => true, 'data' => $license], 201);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param SoftwareProductLicense $license
     * @return JsonResponse
     */
    public function show(SoftwareProductLicense $license): JsonResponse
    {
        try {
            /* $license = SoftwareProductLicense::findOrFail($id); */
            return response()->json(['success' => true, 'data' => $license]);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param SoftwareProductLicense $license
     * @return JsonResponse
     */
    public function update(Request $request, SoftwareProductLicense $license): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'serial' => 'required|string|max:100|unique:software_product_licenses,serial,' . $id,
                'software_product_sku' => 'required|string|exists:software_products,sku',
                'operative_system_slug' => 'required|string|exists:operative_systems,slug',
                // Add other validation rules as needed
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $license->update($validator->validated());
            return response()->json(['success' => true, 'data' => $license]);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param SoftwareProductLicense $license
     * @return JsonResponse
     */
    public function destroy(SoftwareProductLicense $license): JsonResponse
    {
        try {
            $license->delete();
            return response()->json(['success' => true, 'message' => 'License deleted successfully']);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }

    // Implement other CRUD methods like show, update, and destroy following the same pattern
}
