<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServicesRequest;
use App\Models\OurService;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use function PHPUnit\Framework\isJson;

class OurServiceController extends Controller
{
    /**
     * Display all services.
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-13
     * @return JsonResponse API response containing all services data
     */
    public function index()
    {
        try {
            // Fetch all records from OurService model
            $services = OurService::select('id','name', 'description', 'image', 'logo', 'created_at')->get();

            // Return a successful API response with the services data
            return api_response(data: $services, message: 'Services retrieved successfully');
        } catch (Exception $e) {
            // Return an error API response if an exception occurs
            return api_response(errors: [$e->getMessage()], message: 'Services retrieved error', code: 500);
        }
    }

    /**
     * Store a new service in the database.
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-14
     * @param ServicesRequest $request Service data from request
     * @return JsonResponse API response indicating success or failure
     */
    public function store(ServicesRequest $request)
    {
        try {
            // Validate incoming request data based on rules defined in ServicesRequest
            $validatedData = $request->validated();

            // Store uploaded image and logo files in the specified directory
            $imagePath = $validatedData['image'] ? storeFile($validatedData['image'], '/images/services') : '/images/services/images/service.jpg ';

            $logoPath = $validatedData['logo'] ? storeFile($validatedData['logo'], '/images/logos') : '/images/services/logos/logo.jpg ';

            $name = $validatedData['name'];
            if (isJson($name)) {
                // Decode the JSON string
                $decodedValue = json_decode($name, true);

                // Check if the decoded value is an array and contains 'value' key
                if (is_array($decodedValue) && isset($decodedValue['value'])) {
                    $name = $decodedValue['value'];
                }
            }

            $description = $validatedData['description'];
            if (isJson($description)) {
                // Decode the JSON string
                $decodedValue = json_decode($description, true);

                // Check if the decoded value is an array and contains 'value' key
                if (is_array($decodedValue) && isset($decodedValue['value'])) {
                    $description = $decodedValue['value'];
                }
            }
            // Create a new service record in the database
            $service = OurService::create([
                'admin_id' => $validatedData['admin_id'],
                'name' => $name,
                'description' => $description,
                'image' => $imagePath,
                'logo' => $logoPath
            ]);
//['id' =>$service->id, 'image' => $service->image, 'logo' => $service->logo]
            // Return a successful API response indicating the service was created
            return api_response(data: $service,message: 'تم إنشاء الخدمة بنجاح');
        } catch (Exception $e) {
            // Return an error API response if an exception occurs during creation
            return api_response(errors: $e->getMessage(), message: 'هناك مشكلة في إنشاء الخدمة', code: 500);
        }
    }

    /**
     * Update an existing service.
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-15
     * @param ServicesRequest $request Updated service data
     * @param OurService $service Service to update
     * @return JsonResponse API response indicating success or failure
     */
    public function update(ServicesRequest $request, OurService $service)
    {
        try {
            // Validate incoming data and retrieve the existing service record
            $validatedData = $request->validated();

            // Check if new images or logos are provided and update them
            $imagePath = !is_null($validatedData['image']) ? editFile($service->image, $validatedData['image'], '/images/services/images') : $service->image;
            $logoPath = !is_null($validatedData['logo']) ? editFile($service->logo, $validatedData['logo'], '/images/services/logos') : $service->logo;

            // Update the service record with new data
            $service->update([
                'admin_id' => $validatedData['admin_id'],
                'name' => $validatedData['name'],
                'description' => $validatedData['description'],
                'image' => $imagePath,
                'logo' => $logoPath
            ]);

            // Return a successful API response indicating the service was updated
            return api_response(message: 'تم تحديث بيانات الخدمة بنجاح');
        } catch (Exception $e) {
            // Return an error API response if an exception occurs during update
            return api_response(errors: $e->getMessage(), message: 'فشل في تحديث بيانات الخدمة', code: 500);
        }
    }

    /**
     * Delete a service from the database.
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-16
     * @param OurService $service Service to delete
     * @return JsonResponse API response indicating success or failure
     */
    public function destroy(OurService $service)
    {
        try {

            removeFile($service->logo);

            removeFile($service->image);

            // Delete the service record
            $service->delete();

            // Return a successful API response indicating the service was deleted
            return api_response(message: 'تم حذف بيانات الخدمة بنجاح');

        } catch (Exception $e) {
            // Return an error API response if an exception occurs during deletion
            return api_response(errors: $e->getMessage(), message: 'فشل في حذف بيانات الخدمة', code: 500);
        }
    }
}

