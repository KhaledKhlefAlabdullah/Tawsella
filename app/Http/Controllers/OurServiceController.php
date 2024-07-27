<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServicesRequest;
use App\Models\OurService;
use Exception;
use function PHPUnit\Framework\is_null;

class OurServiceController extends Controller
{
    /**
     * Display all services.
     * @return mixed API response containing all services data
     */
    public function index()
    {
        try {
            // Fetch all records from OurService model
            $services = OurService::all();

            // Return a successful API response with the services data
            return api_response(data: $services, message: 'تم إرجاع بيانات الخدمات بنجاح');
        } catch (Exception $e) {
            // Return an error API response if an exception occurs
            return api_response(errors: $e->getMessage(), message: 'فشل في استرجاع بيانات الخدمات', code: 500);
        }
    }

    /**
     * Store a new service in the database.
     * @param ServicesRequest $request Service data from request
     * @return mixed API response indicating success or failure
     */
    public function store(ServicesRequest $request)
    {
        try {
            // Validate incoming request data based on rules defined in ServicesRequest
            $validatedData = $request->validated();

            // Store uploaded image and logo files in the specified directory
            $imagePath = $validatedData->input('image') ? storeFile($validatedData->input('image'), '/images/services') : '/images/services/service.jpg';

            $logoPath = $validatedData->input('logo') ? storeFile($validatedData->input('logo'), '/images/services') : '/images/services/logo.jpg';

            // Create a new service record in the database
            OurService::create([
                'admin_id' => $validatedData['admin_id'],
                'service_name' => $validatedData['service_name'],
                'service_description' => $validatedData['service_description'],
                'image' => $imagePath,
                'logo' => $logoPath
            ]);

            // Return a successful API response indicating the service was created
            return api_response(message: 'تم إنشاء الخدمة بنجاح');
        } catch (Exception $e) {
            // Return an error API response if an exception occurs during creation
            return api_response(errors: $e->getMessage(), message: 'هناك مشكلة في إنشاء الخدمة', code: 500);
        }
    }

    /**
     * Update an existing service.
     * @param ServicesRequest $request Updated service data
     * @param string $id Service ID to update
     * @return mixed API response indicating success or failure
     */
    public function update(ServicesRequest $request, string $id)
    {
        try {
            // Validate incoming data and retrieve the existing service record
            $validatedData = $request->validated();
            $service = getAndCheckModelById(OurService::class, $id);

            // Check if new images or logos are provided and update them
            $imagePath = !is_null($validatedData['image']) ? editFile($service->image, $validatedData['image'], '/images/services') : $service->image;
            $logoPath = !is_null($validatedData['logo']) ? editFile($service->logo, $validatedData['logo'], '/images/services') : $service->logo;

            // Update the service record with new data
            $service->update([
                'admin_id' => $validatedData['admin_id'],
                'service_name' => $validatedData['service_name'],
                'service_description' => $validatedData['service_description'],
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
     * @param string $id Service ID to delete
     * @return mixed API response indicating success or failure
     */
    public function destroy(string $id)
    {
        try {
            // Retrieve the service record by ID
            $service = getAndCheckModelById(OurService::class, $id);

            removeFile($service->logo);

            removeFile($service->image);

            // if ($logoMessage == 'falied' || $imageMessage == 'falied')
            //     return api_response(message: 'لم يتم ايجاد البيانات', code: 404);

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

