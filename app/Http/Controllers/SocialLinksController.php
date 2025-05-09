<?php

namespace App\Http\Controllers;

use App\Http\Requests\SocialLinksRequest;
use App\Models\AboutUs;
use Exception;
use Illuminate\Http\JsonResponse;

class SocialLinksController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return JsonResponse
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-30
     */
    public function index()
    {
        $socialLinks = AboutUs::select('id', 'title', 'description as link', 'image as icon')
            ->where('is_social', true)
            ->orderBy('created_at', 'desc')
            ->get();
        return api_response(data: $socialLinks, message: 'تم جلب روابط مواقع التواصل الاجتماعي بنجاح');
    }

    /**
     * Store or update the general social link details
     * @param SocialLinksRequest $request
     * @return JsonResponse to view page
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-30
     */
    public function store(SocialLinksRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();

            if (array_key_exists('icon', $validatedData)) {
                $imagePath = storeFile($validatedData['icon'], '/images/aboutUs/icons');
            }
            $aboutUs = AboutUs::create([
                'admin_id' => $validatedData['admin_id'],
                'title' => $validatedData['title'],
                'description' => $validatedData['link'],
                'is_social' => true,
                'image' => $imagePath ?? null
            ]);

            $data = [
                'title' => $aboutUs->title,
                'link' => $aboutUs->description,
                'icon' => $aboutUs->image,
            ];
            return api_response(data: $data, message: 'تم إنشاء رابط موقع التواصل الاجتماعي بنجاح');
        } catch (Exception $e) {
            return api_response(message: 'خطأ في إنشاء رابط موقع التواصل الاجتماعي', code: 500, errors: [$e->getMessage()]);
        }
    }

    /**
     * Update additional info records
     * @param SocialLinksRequest $request
     * @para string $aboutUs
     * @return JsonResponse to view page
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-
     */
    public function update(SocialLinksRequest $request, string $aboutUs): JsonResponse
    {
        try {

            $validatedData = $request->validated();
            $aboutUs = AboutUs::find($aboutUs);

            $imagePath = array_key_exists('icon', $validatedData) ? editFile($aboutUs->image, '/images/aboutUs/icons', $validatedData['icon']) : $aboutUs->image;
            $aboutUs->update([
                'title' => $validatedData['title'] ?? $aboutUs->title,
                'description' => $validatedData['link'] ?? $aboutUs->description,
                'image' => $imagePath
            ]);

            $data = [
                'title' => $aboutUs->title,
                'link' => $aboutUs->description,
                'icon' => $aboutUs->image,
            ];
            return api_response(data: $data, message: 'تم تعديل رابط موقع التواصل الاجتماعي بنجاح');

        } catch (Exception $e) {
            return api_response(message: 'خطأ في تعديل رابط موقع التواصل الاجتماعي', code: 500, errors: [$e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param string $aboutUs
     * @return JsonResponse to view page
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-
     */
    public function destroy(string $aboutUs)
    {
        try {
            $aboutUs = AboutUs::find($aboutUs);
            removeFile($aboutUs->image);
            $aboutUs->delete();
            return api_response(message: 'تم حذف رابط موقع التواصل الاجتماعي بنجاح.');
        } catch (Exception $e) {
            return api_response(message: 'خطأ في حذف رابط موقع التواصل الاجتماعي', code: 500, errors: [$e->getMessage()]);
        }
    }
}
