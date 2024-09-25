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
        $query = $this->paginationService->applyFilters($query, $request);
        $query = $this->paginationService->applySorting($query, $request);
        $services = $this->paginationService->paginate($query, $request);
        return api_response(data: $services, message: 'Successfully getting messages', pagination: get_pagination($services, $request));
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
     * @param OurService $service Service to update
     * @return JsonResponse redirect back response
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-17
     */
    public function update(ServicesRequest $request, OurService $service)
    {
        try {
            $validatedData = $request->validated();

            $imagePath = !is_null($validatedData['image']) ? editFile($service->image, $validatedData['image'], '/images/services/images') : $service->image;
            $logoPath = !is_null($validatedData['logo']) ? editFile($service->logo, $validatedData['logo'], '/images/services/logos') : $service->logo;

            $service->update([
                'admin_id' => $validatedData['admin_id'],
                'name' => $validatedData['name'],
                'description' => $validatedData['description'],
                'image' => $imagePath,
                'logo' => $logoPath
            ]);

            return api_response(data: $service, message: 'Successfully updated our services');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'Error in updated our services', code: 500);
        }
    }

    /**
     * Delete a service from the database.
     * @param OurService $service Service to delete
     * @return JsonResponse API response indicating success or failure
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-18
     */
    public function destroy(OurService $service)
    {
        try {

            removeFile($service->logo);
            removeFile($service->image);
            $service->delete();

            return api_response(message: 'Successfully deleted our services');
        } catch (Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'Error in deleted our services', code: 500);
        }
    }
}
