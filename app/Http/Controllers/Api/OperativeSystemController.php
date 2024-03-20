<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OperativeSystem;
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
            return response()->json(['success' => true, 'data' => $operativeSystems]);
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
                'slug' => 'required|string|unique:operative_systems,slug|max:255',
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
     * @param OperativeSystem $operativeSystem
     * @return JsonResponse
     */
    public function show(OperativeSystem $operativeSystem): JsonResponse
    {
        try {
            return response()->json(['success' => true, 'data' => $operativeSystem]);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param OperativeSystem $operativeSystem
     * @return JsonResponse
     */
    public function update(Request $request, OperativeSystem $operativeSystem): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'slug' => 'required|string|unique:operative_systems,slug,' . $operativeSystem->id . '|max:255',
                // Add other validation rules as needed
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $operativeSystem->update($validator->validated());
            return response()->json(['success' => true, 'data' => $operativeSystem]);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param OperativeSystem $operativeSystem
     * @return JsonResponse
     */
    public function destroy(OperativeSystem $operativeSystem): JsonResponse
    {
        try {
            $operativeSystem->delete();
            return response()->json(['success' => true, 'message' => 'Operative system deleted successfully']);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }
}
