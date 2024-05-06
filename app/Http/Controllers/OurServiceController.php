<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServicesRequest;
use App\Models\OurService;
use Exception;
use Illuminate\Http\Request;

use function Laravel\Prompts\error;
use function PHPUnit\Framework\isNull;

class OurServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return mixed api response with services data
     */
    public function index()
    {
        try {

            // get services data
            $services = OurService::all();

            // return api response with services data
            return api_response(data: $services, message: 'تم ارجاع بيانات الخدمات بنجاح');
        } catch (Exception $e) {
            // handle the exception and return api response with errors and error message
            return api_response(errors: $e->getMessage(), message: 'هناك مشكلة في ارجاع بيانات الخدمات', code: 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param request $request is the service i want to add details
     * @return mixed api response with success message
     */
    public function store(ServicesRequest $request)
    {
        try {

            // validate the data
            $validatedData = $request->validated();


            // store the files in images folder
            $imagePath = storeFile($validatedData['image'], '/images/services');
            $logoPath = storeFile($validatedData['logo'], '/images/services');

            // create new services
            OurService::create([
                'admin_id' => $validatedData['admin_id'],
                'service_name' => $validatedData['service_name'],
                'service_description' => $validatedData['service_description'],
                'image' => $imagePath,
                'logo' => $logoPath
            ]);

            // return api response with success message
            return api_response(message: 'تم إنشاء خدمة بنجاح');
        } catch (Exception $e) {
            // handle exceptions and return api response with error message
            return api_response(errors: $e->getMessage(), message: 'هناك مشكلة في إنشاء خدمة', code: 500);
        }
    }


    /**
     * Update the specified resource in storage.
     * @param string $id is service id i want to edit
     * @param request $request is new data for update the service
     * @return mixed api response with success message
     */
    public function update(ServicesRequest $request, string $id)
    {
        try {

            // validate the data
            $validatedData = $request->validated();
            // get the service
            $service = getAndCheckModelById(OurService::class, $id);
            $oldImagePath = $service->image;
            $oldLogoPath = $service->logo;

            $image = $validatedData['image'];
            $logo = $validatedData['logo'];

            // store the files in images folder
            if (!isNull($image))
                $imagePath = editFile($oldImagePath, $image, '/images/services');

            if (!isNull($logo))
                $logoPath = editFile($oldLogoPath, $logo, '/images/services');

            // update service data
            $service->update([
                'admin_id' => $validatedData['admin_id'],
                'service_name' => $validatedData['service_name'],
                'service_description' => $validatedData['service_description'],
                'image' => $imagePath,
                'logo' => $logoPath
            ]);
            // return api response with success message
            return api_response(message: 'تم تحديث بيانات الخدمة بنجاح');
        } catch (Exception $e) {
            // handle exceptions and return api response with error message
            return api_response(errors: $e->getMessage(), message: 'هناك خطأ في تحديث بيانات الخدمة', code: 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param string $id is service id i want to delete
     * @return mixed api response with success message
     */
    public function destroy(string $id)
    {
        try {
            // get the service
            $service = getAndCheckModelById(OurService::class, $id);
            // delete service
            $service->delete();

            // return api response with success message
            return api_response(message: 'تم حذف بيانات الخدمة بنجاح');
        } catch (Exception $e) {
            // handle exceptions and return api response with error message
            return api_response(errors: $e->getMessage(), message: 'هناك خطأ في حذف بيانات الخدمة', code: 500);
        }
    }
}
