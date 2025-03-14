<?php

namespace App\Http\Controllers;

use App\Models\MapApiKey;
use Illuminate\Http\Request;

class MapApiKeyController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return api_response(data: MapApiKey::first(), message: 'Success to get map api key');
    }

    /**
     * Store a newly created resource in storage Or update exist one.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'api_key' => ['required', 'string'],
            ]);

            $apiKey = MapApiKey::first();

            if (!$apiKey) {
                $apiKey = MapApiKey::create($validatedData);
            } else {
                $apiKey->update($validatedData);
            }
            return api_response(data: $apiKey, message: 'Success to create map api key');
        } catch (\Exception $e) {
            return api_response(message: 'Failed to get map api key', code: 500, errors: $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param MapApiKey $mapApiKey
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(MapApiKey $mapApiKey)
    {
        try {
            $mapApiKey->delete();
            return api_response(message: 'Success to delete map api key');
        }catch (\Exception $e) {
            return api_response(message: 'Error in delete map api key', code: 500, errors: $e->getMessage());
        }
    }
}
