<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OperativeSystem;
use App\Models\SoftwareProductLicense;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OperativeSystemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $operativeSystems = OperativeSystem::all();
            return response()->json(['success' => true, 'operative_systems' => $operativeSystems]);
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
                'slug' => 'required|string|unique:operative_systems,slug|min:3|max:100',
                // Add other validation rules as needed
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $operativeSystem = OperativeSystem::create($validator->validated());
            return response()->json(['success' => true, 'data' => $operativeSystem], 201);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param String $slug
     * @return JsonResponse
     */
    public function show(String $slug): JsonResponse
    {
        try {
            $validator = Validator::make([
                'slug' => $slug
            ], 
            [
                'slug' => 'required|string|min:3|max:100',
                // Add other validation rules as needed
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            $operativeSystem = OperativeSystem::where('slug', $slug)->first();
            return response()->json(['success' => true, 'operative_system' => $operativeSystem]);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param String $slug
     * @return JsonResponse
     */
    public function update(Request $request, String $slug): JsonResponse
    {
        try {
            if (!$request->hasAny(['name', 'slug'])) {
                return response()->json(['success' => false, 'message' => 'No data to update'], 422);
            }

            $rules = [];
        
            // Add validation rules for 'name' if present in the request
            if ($request->has('name')) {
                $rules['name'] = 'required|string|min:3|max:100';
            }
            
            // Add validation rules for 'sku' if present in the request
            if ($request->has('slug')) {
                $rules['slug'] = 'required|string||min:3|max:100';
            }
            
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            $operativeSystem = OperativeSystem::where('slug', $slug)->first();
            if ($operativeSystem) {
                OperativeSystem::where('slug', $slug)->update($validator->validated());
                return response()->json(['success' => true, 'data' => $validator->validated()]);
            } else {
                return response()->json(['success' => false, 'message' => 'Operative system not found'], 404);
            }
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param String $slug
     * @return JsonResponse
     */
    public function destroy(String $slug): JsonResponse
    {
        try {
            $validator = Validator::make([
                'slug' => $slug
            ], 
            [
                'slug' => 'required|string|min:3|max:100',
                // Add other validation rules as needed
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            $operativeSystem = OperativeSystem::where('slug', $slug)->first();
            if ($operativeSystem) {
                SoftwareProductLicense::where('operative_system_slug', $slug)->delete();
                OperativeSystem::where('slug', $slug)->delete();
                return response()->json(['success' => true, 'message' => 'Operative system deleted. All the corresponding Licenses where deleted as well']);
            } else {
                return response()->json(['success' => false, 'message' => 'Operative system not found'], 404);
            };
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }
}
