<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SoftwareProduct;
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
            return response()->json(['success' => true, 'data' => $softwareProducts]);
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
                'sku' => 'required|string|unique:software_products,sku|min:10|max:10',
                // Add other validation rules as needed
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $softwareProduct = SoftwareProduct::create($validator->validated());
            return response()->json(['success' => true, 'data' => $softwareProduct], 201);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param SoftwareProduct $softwareProduct
     * @return JsonResponse
     */
    public function show(SoftwareProduct $softwareProduct): JsonResponse
    {
        try {
            return response()->json(['success' => true, 'data' => $softwareProduct]);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param SoftwareProduct $softwareProduct
     * @return JsonResponse
     */
    public function update(Request $request, SoftwareProduct $softwareProduct): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'sku' => 'required|string|unique:software_products,sku|min:10|max:10',
                // Add other validation rules as needed
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $softwareProduct->update($validator->validated());
            return response()->json(['success' => true, 'data' => $softwareProduct]);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param SoftwareProduct $softwareProduct
     * @return JsonResponse
     */
    public function destroy(SoftwareProduct $softwareProduct): JsonResponse
    {
        try {
            $softwareProduct->delete();
            return response()->json(['success' => true, 'message' => 'Software product deleted successfully']);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }
}
