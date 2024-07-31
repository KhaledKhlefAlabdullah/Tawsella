<?php

namespace App\Http\Controllers;

use App\Http\Requests\AboutUsRequest;
use App\Models\AboutUs;
use Exception;


class AboutUsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return mixed
     */
    public function index()
    {
        try {
            $aboutUsRecords = AboutUs::select('id','title', 'description', 'complaints_number', 'image')->where('is_general', true)->first();
            $additional_info = AboutUs::select('id','title', 'description', 'image')->where('is_general', false)->get();

            return api_response(data: ['generalAboutus' => $aboutUsRecords, 'additionalInfo' => $additional_info], message: 'نجحنا في الحصول على التفاصيل عنا');
        } catch (Exception $e) {
            return api_response(errors: $e->getMessage(), message: 'فشل في الحصول على التفاصيل عنا', code: 500);
        }
    }


    /**
     * Get the additional information
     * @return mixed
     */
    public function getAdditionInformation()
    {
        try {
            $data = AboutUs::select('id','title', 'description', 'image')->where('is_general', false)->get();

            return api_response(data: $data, message: 'الحصول على معلومات إضافية ناجحة');
        } catch (Exception $e) {
            return api_response(errors: $e->getMessage(), message: 'الحصول على خطأ معلومات إضافية', code: 500);
        }
    }

    /**
     * Store or update the general about us details
     * @param AboutUsRequest $request
     * @return mixed
     */
    public function storeOrUpdate(AboutUsRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $aboutUs = AboutUs::where('is_general', true)->first();

            if (is_null($aboutUs)) {
                $imagePath = storeFile($validatedData['image'], '/images/aboutUs');
                AboutUs::create([
                    'title' => $validatedData['title'],
                    'description' => $validatedData['description'],
                    'complaints_number' => $validatedData['complaints_number'],
                    'is_general' => true,
                    'image' => $imagePath
                ]);

                $messag = 'تم إنشاء نبذه عنا بنجاح';
            } else {
                $imagePath = $validatedData['image'] ? editFile($aboutUs->image, '/images/aboutUs', $validatedData['image']) : $aboutUs->image;
                $aboutUs->update([
                    'title' => $validatedData['title'],
                    'description' => $validatedData['description'],
                    'complaints_number' => $validatedData['complaints_number'],
                    'image' => $imagePath
                ]);
                $messag = 'تحديث نبذة عنا بنجاح';
            }
            return api_response(data: $validatedData, message: $messag);
        } catch (Exception $e) {
            return api_response(errors: $e->getMessage(), message: 'فشل في إنشاء أو تحديث نبذة عنا', code: 500);
        }
    }

    /**
     * Create additional info records
     * @param AboutUsRequest $request
     * @return mixed
     */
    public function storeAdditionalInfo(AboutUsRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $imagePath = storeFile($validatedData['image'], '/images/aboutUs/additional');
            AboutUs::create([
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
                'image' => $imagePath
            ]);
            return api_response(data: $validatedData, message: 'تم إضافة معلومات إضافية بنجاح.');
        } catch (Exception $e) {
            return api_response(errors: $e->getMessage(), message: 'فشل في إضافة معلومات إضافية', code: 500);
        }
    }

    /**
     * Update additional info records
     * @param AboutUsRequest $request
     * @param AboutUs $aboutUs
     * @return mixed
     */
    public function updateAdditionalInfo(AboutUsRequest $request, string $id)
    {
        try {
            $validatedData = $request->validated();
            $aboutUs = getAndCheckModelById(AboutUs::class, $id);
            $imagePath = $validatedData['image'] ? editFile($aboutUs->image, '/images/aboutUs/additional', $validatedData['image']) : $aboutUs->image;
            $aboutUs->update([
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
                'image' => $imagePath
            ]);
            return api_response(data: $validatedData, message: 'تم تعديل البيانات بنجاح.');
        } catch (Exception $e) {
            return api_response(errors: $e->getMessage(), message: 'فشل في تعديل البيانات', code: 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param AboutUs $aboutUs
     * @return mixed
     */
    public function destroy(string $id)
    {
        try {
            $aboutUs = getAndCheckModelById(AboutUs::class, $id);
            $message = removeFile($aboutUs->image);
            if ($message == 'falied')
                return api_response(message: 'لم يتم ايجاد البيانات', code: 404);

            $aboutUs->delete();
            return api_response(message: 'تم حذف البيانات بنجاح.');
        } catch (Exception $e) {
            return api_response(errors: $e->getMessage(), message: 'فشل في حذف البيانات', code: 500);
        }
    }
}
