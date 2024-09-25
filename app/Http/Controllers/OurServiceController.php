<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServicesRequest;
use App\Models\OurService;
use App\Services\PaginationService;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use function Laravel\Prompts\error;

class OurServiceController extends Controller
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
        // Fetch all records from OurService model
        $query = OurService::query();

        $our_services = $this->paginationService->applyPagination($query, $request);
        return api_response(data: $our_services->items(), message: 'Successfully getting messages', pagination: get_pagination($our_services, $request));
    }

    /**
     * Get our service details
     * @param OurService $our_service
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(OurService $our_service){
        return api_response(data: $our_service, message: 'Successfully getting our service details');
    }

    /**
     * Store a new service in the database.
     * @param ServicesRequest $request Service data from request
     * @return JsonResponse response indicating success or failure
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-16
     */
    public function store(ServicesRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $imagePath = $validatedData['image'] ? storeFile($validatedData['image'], '/images/services') : '/images/services/images/service.jpg';

            $logoPath = $validatedData['logo'] ? storeFile($validatedData['logo'], '/images/logos') : '/images/services/logos/logo.jpg';

            $ourService = OurService::create([
                'admin_id' => $validatedData['admin_id'],
                'name' => $validatedData['name'],
                'description' => $validatedData['description'],
                'image' => $imagePath,
                'logo' => $logoPath
            ]);

            return api_response(data: $ourService, message: 'Successfully created our services');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'Error in creating our services', code: 500);
        }
    }

    /**
     * Update an existing service.
     * @param ServicesRequest $request Updated service data
     * @param OurService $our_service Service to update
     * @return JsonResponse redirect back response
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-17
     */
    public function update(ServicesRequest $request, OurService $our_service)
    {
        try {
            $validatedData = $request->validated();

            $imagePath = $validatedData['image'] ? editFile($our_service->image, '/images/services/images', $validatedData['image']) : $our_service->image;
            $logoPath = $validatedData['logo'] ? editFile($our_service->logo, '/images/services/logos', $validatedData['logo']) : $our_service->logo;

            $our_service->update([
                'admin_id' => $validatedData['admin_id'],
                'name' => $validatedData['name'],
                'description' => $validatedData['description'],
                'image' => $imagePath,
                'logo' => $logoPath
            ]);

            return api_response(data: $our_service, message: 'Successfully updated our services');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'Error in updated our services', code: 500);
        }
    }

    /**
     * Delete a service from the database.
     * @param OurService $our_service Service to delete
     * @return JsonResponse API response indicating success or failure
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-18
     */
    public function destroy(OurService $our_service)
    {
        try {

            removeFile($our_service->logo);
            removeFile($our_service->image);
            $our_service->delete();

            return api_response(message: 'Successfully deleted our services');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'Error in deleted our services', code: 500);
        }
    }
}
