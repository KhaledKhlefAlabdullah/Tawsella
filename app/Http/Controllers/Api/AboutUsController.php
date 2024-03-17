<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AboutUsResource;
use App\Models\AboutUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AboutUsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $aboutUsRecords = AboutUs::all();
        return response()->json(['data' => AboutUsResource::collection($aboutUsRecords)]);
    }

    /**
     * Display the specified resource.
     *
     * @param AboutUs $aboutUs
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(AboutUs $aboutUs)
    {
        return response()->json(['data' => new AboutUsResource($aboutUs)]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'complaints_number' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $aboutUs = AboutUs::create($request->all());

        return response()->json(['data' => new AboutUsResource($aboutUs)], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param AboutUs $aboutUs
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, AboutUs $aboutUs)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'complaints_number' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $aboutUs->update($request->all());

        return response()->json(['data' => new AboutUsResource($aboutUs)], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param AboutUs $aboutUs
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(AboutUs $aboutUs)
    {
        $aboutUs->delete();

        return response()->json(['message' => 'Deleted successfully'], 204);
    }
}
