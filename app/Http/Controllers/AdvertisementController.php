<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdvertisementsRequest;
use App\Models\Advertisement;
use App\Services\PaginationService;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class AdvertisementController extends Controller
{
    protected $paginationService;

    public function __construct(PaginationService $paginationService)
    {
        $this->paginationService = $paginationService;
    }

    /**
     * Display all services.
     * @return JsonResponse response containing all services data
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-15
     */
    public function index(Request $request)
    {
        $validAdvertisements = Advertisement::query()->where('validity_date', '>=', date('Y-m-d'));
        $validAdvertisements = $this->paginationService->applyPagination($validAdvertisements, $request);

        $user = $request->user();
        if ($user->hasRole('Admin')) {
            $invalidAdvertisements = Advertisement::query()->where('validity_date', '<', date('Y-m-d'));
            $invalidAdvertisements = $this->paginationService->applyPagination($invalidAdvertisements, $request);
            return api_response(data: ['validAdvertisements' => $validAdvertisements, 'invalidAdvertisements' => $invalidAdvertisements]);
        }

        return api_response(data: $validAdvertisements->items(), message: 'Successfully getting advertisements', pagination: get_pagination($validAdvertisements, $request));
    }

    /**
     * Get advertisements details
     * @param Advertisement $our_service
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Advertisement $our_service){
        return api_response(data: $our_service, message: 'Successfully getting advertisements details');
    }

    /**
     * Store a new service in the database.
     * @param AdvertisementsRequest $request Service data from request
     * @return JsonResponse response indicating success or failure
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-16
     */
    public function store(AdvertisementsRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $imagePath = $validatedData['image'] ? storeFile($validatedData['image'], '/images/advertisements/images') : '/images/services/images/service.jpg';

            $logoPath = $validatedData['logo'] ? storeFile($validatedData['logo'], '/images/advertisements/logos') : '/images/services/logos/logo.jpg';

            $Advertisement = Advertisement::create([
                'admin_id' => $validatedData['admin_id'],
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
                'image' => $imagePath,
                'logo' => $logoPath,
                'validity_date' => $validatedData['validity_date'],
            ]);

            return api_response(data: $Advertisement, message: 'Successfully created advertisementss');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'Error in creating advertisementss', code: 500);
        }
    }

    /**
     * Update an existing service.
     * @param AdvertisementsRequest $request Updated service data
     * @param Advertisement $our_service Service to update
     * @return JsonResponse redirect back response
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-17
     */
    public function update(AdvertisementsRequest $request, Advertisement $our_service)
    {
        try {
            $validatedData = $request->validated();

            $imagePath = $validatedData['image'] ? editFile($our_service->image, '/images/advertisements/images', $validatedData['image']) : $our_service->image;
            $logoPath = $validatedData['logo'] ? editFile($our_service->logo, '/images/advertisements/logos', $validatedData['logo']) : $our_service->logo;

            $our_service->update([
                'admin_id' => $validatedData['admin_id'],
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
                'image' => $imagePath,
                'logo' => $logoPath
            ]);

            return api_response(data: $our_service, message: 'Successfully updated advertisementss');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'Error in updated advertisementss', code: 500);
        }
    }

    /**
     * Delete a service from the database.
     * @param Advertisement $our_service Service to delete
     * @return JsonResponse API response indicating success or failure
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-18
     */
    public function destroy(Advertisement $our_service)
    {
        try {

            removeFile($our_service->logo);
            removeFile($our_service->image);
            $our_service->delete();

            return api_response(message: 'Successfully deleted advertisementss');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'Error in deleted advertisementss', code: 500);
        }
    }
}
