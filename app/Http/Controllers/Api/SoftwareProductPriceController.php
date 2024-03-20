<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SoftwareProductPrice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SoftwareProductPriceController extends Controller
{

    /**
    * Display a listing of the resource.
    *
    * @return JsonResponse
    */
    public function index(): JsonResponse
    {
        try {
            $licenses = SoftwareProductPrice::all();
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
                'value' => 'required|numeric',
                'software_product_sku' => 'required|string|exists:software_products,sku',
                'operative_system_slug' => 'required|string|exists:operative_systems,slug',
                // Add other validation rules as needed
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $price = SoftwareProductPrice::create($validator->validated());
            return response()->json(['success' => true, 'data' => $license], 201);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param SoftwareProductPrice $price
     * @return JsonResponse
     */
    public function show(SoftwareProductPrice $price): JsonResponse
    {
        try {
            $price = SoftwareProductPrice::findOrFail($id);
            return response()->json(['success' => true, 'data' => $license]);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param SoftwareProductPrice $price
     * @return JsonResponse
     */
    public function update(Request $request, SoftwareProductPrice $price): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'value' => 'required|numeric',
                'software_product_sku' => 'required|string|exists:software_products,sku',
                'operative_system_slug' => 'required|string|exists:operative_systems,slug',
                // Add other validation rules as needed
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $price->update($validator->validated());
            return response()->json(['success' => true, 'data' => $license]);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param SoftwareProductPrice $price
     * @return JsonResponse
     */
    public function destroy($price): JsonResponse
    {
        try {
            $price->delete();
            return response()->json(['success' => true, 'message' => 'License deleted successfully']);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }
}
