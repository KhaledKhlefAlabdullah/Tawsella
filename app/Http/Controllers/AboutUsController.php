<?php

namespace App\Http\Controllers;

use App\Http\Requests\AboutUsRequest;
use App\Models\AboutUs;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function Laravel\Prompts\error;

class AboutUsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return JsonResponse
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-30
     */
    public function index()
    {
        $aboutUsRecords = AboutUs::select('id', 'title', 'description', 'complaints_number', 'image')->where('is_general', true)->orderBy('created_at', 'desc')->get();
        $additional_info = AboutUs::select('id', 'title', 'description', 'image')->where(['is_general' => false, 'is_social' => false])->orderBy('created_at', 'desc')->get();
        return api_response(data: ['aboutUsRecords' => $aboutUsRecords, 'additional_info' => $additional_info], message: 'تم جلب معلومات حولنا بنجاح');
    }

    /**
     * Store or update the general about us details
     * @param AboutUsRequest $request
     * @return JsonResponse to view page
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-30
     */
    public function storeOrUpdate(AboutUsRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $aboutUs = AboutUs::where('is_general', true)->first();

            if (is_null($aboutUs)) {
                $imagePath = storeFile($validatedData['image'], '/images/aboutUs');
                AboutUs::create([
                    'admin_id' => $validatedData['admin_id'],
                    'title' => $validatedData['title'],
                    'description' => $validatedData['description'],
                    'complaints_number' => $validatedData['complaints_number'],
                    'is_general' => true,
                    'image' => $imagePath
                ]);

                $message = 'تم انشاء ملعومات حولنا بنجاح';
            } else {
                $imagePath = $validatedData['image'] ? editFile($aboutUs->image, '/images/aboutUs', $validatedData['image']) : $aboutUs->image;
                $aboutUs->update([
                    'title' => $validatedData['title'] ?? $aboutUs->title,
                    'description' => $validatedData['description'] ?? $aboutUs->description,
                    'complaints_number' => $validatedData['complaints_number'] ?? $aboutUs->complaints_number,
                    'image' => $imagePath ?? $aboutUs->image
                ]);

                $message = 'تم تحديث معلومات حولنا بنجاح';
            }

            return api_response(data: $aboutUs, message: $message);
        } catch (Exception $e) {
            return api_response(message: 'هناك خطأ في معالجة البيانات', code: 500, errors: [$e->getMessage()]);
        }
    }

    /**
     * Create additional info records
     * @param AboutUsRequest $request
     * @return JsonResponse to view page
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-
     */
    public function storeAdditionalInfo(AboutUsRequest $request)
    {
        try {

            $validatedData = $request->validated();
            $imagePath = storeFile($validatedData['image'], '/images/aboutUs/additional');
            $data = AboutUs::create([
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
                'image' => $imagePath
            ]);

            return api_response(data: $data, message: 'تم اضافة المعلومات الاضافية بنجاح');

        } catch (Exception $e) {
            return api_response(message: 'خطأ في اضافة المعلومات الاضافية', code: 500, errors: [$e->getMessage()]);
        }
    }

    /**
     * Update additional info records
     * @param AboutUsRequest $request
     * @param AboutUs $aboutUs
     * @return JsonResponse to view page
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-
     */
    public function updateAdditionalInfo(AboutUsRequest $request, AboutUs $aboutUs)
    {
        try {

            $validatedData = $request->validated();
            $imagePath = $validatedData['image'] ? editFile($aboutUs->image, '/images/aboutUs/additional', $validatedData['image']) : $aboutUs->image;
            $aboutUs->update([
                'title' => $validatedData['title'] ?? $aboutUs->title,
                'description' => $validatedData['description'] ?? $aboutUs->description,
                'image' => $imagePath ?? $aboutUs->image
            ]);
            return api_response(data: $aboutUs, message: 'Updated additional info.');

        } catch (Exception $e) {
            return api_response(message: 'Error in update additional info.', code: 500, errors: [$e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param AboutUs $aboutUs
     * @return JsonResponse to view page
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-
     */
    public function destroy(AboutUs $aboutUs)
    {
        try {
            removeFile($aboutUs->image);
            $aboutUs->delete();
            return api_response(message: 'Successfully deleted about us.');
        } catch (Exception $e) {
            return api_response(message: 'Error in deleted about us', code: 500, errors: [$e->getMessage()]);
        }
    }
}
