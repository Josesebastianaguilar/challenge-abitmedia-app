<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $services = Service::all();
            return response()->json(['success' => true, 'services' => $services]);
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
                'sku' => 'required|string|unique:services,sku|min:10|max:10',
                'price' => 'required|numeric|min:0',
                // Add other validation rules as needed
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $service = Service::create($validator->validated());
            return response()->json(['success' => true, 'service' => $service], 201);
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
            $validator = Validator::make([
                'sku' => $sku
            ], 
            [
                'sku' => 'required|string|min:10|max:10|exists:services,sku',
                // Add other validation rules as needed
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            $service = Service::where('sku', $sku)->first();
            if ($service) {
                return response()->json(['success' => true, 'service' => $service]);
            } else {
                return response()->json(['success' => false, 'message' => 'Service not found'], 404);
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
            if (!$request->hasAny(['name', 'price'])) {
                return response()->json(['success' => false, 'message' => 'No data to update'], 422);
            }

            $rules = [];
            $comparator = [];
            // Add validation rules for 'name' if present in the request
            if ($request->has('name')) {
                $rules['name'] = 'required|string|min:3|max:100';
                $comparator['name'] = $request->get('name');
            }

            // Add validation rules for 'price' if present in the request
            if ($request->has('price')) {
                $rules['price'] = 'required|numeric|min:0';
                $comparator['price'] = $request->get('price');
            }
            // Add validation rules for 'price' if present in the request
            if ($request->has('sku')) {
                $rules['sku'] = 'required|string|unique:services,sku|min:10|max:10';
                $comparator['sku'] = $request->get('sku');
            } else {
                $comparator['sku'] = $sku;
                $rules['sku'] = 'required|string|min:10|max:10|exists:services,sku';
            }
            
            $validator = Validator::make($comparator, $rules);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            $service = Service::where('sku', $sku)->first();
            if ($service) {
                Service::where('sku', $sku)->update($validator->validated());
                return response()->json(['success' => true, 'data' => $validator->validated()]);
            } else {
                return response()->json(['success' => false, 'message' => 'Service not found'], 404);
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
            $validator = Validator::make([
                'sku' => $sku
            ], 
            [
                'sku' => 'required|string|min:10|max:10|exists:services,sku',
                // Add other validation rules as needed
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            $service = Service::where('sku', $sku)->first();
            if ($service) {
                Service::where('sku', $sku)->delete();
                return response()->json(['success' => true, 'message' => 'Service deleted successfully']);
            } else {
                return response()->json(['success' => false, 'message' => 'Service not found'], 404);
            }
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }
}
