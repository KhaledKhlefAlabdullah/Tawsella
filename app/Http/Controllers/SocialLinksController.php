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
        return api_response(data: $socialLinks, message: 'Successfully retrieved social links.');
    }

    /**
     * Store or update the general social link details
     * @param SocialLinksRequest $request
     * @return JsonResponse to view page
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-30
     */
    public function store(SocialLinksRequest $request)
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
            return api_response(data: $data, message: 'Successfully created social link');
        } catch (Exception $e) {
            return api_response(message: 'There error in processed data.', code: 500, errors: [$e->getMessage()]);
        }
    }

    /**
     * Update additional info records
     * @param SocialLinksRequest $request
     * @param AboutUs $aboutUs
     * @return JsonResponse to view page
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-
     */
    public function update(SocialLinksRequest $request, AboutUs $aboutUs)
    {
        try {

            $validatedData = $request->validated();
            $imagePath = array_key_exists('icon', $validatedData) ? editFile($aboutUs->image, '/images/aboutUs/icons', $validatedData['icon']) : $aboutUs->image;
            $aboutUs->update([
                'title' => $validatedData['title'] ?? $aboutUs->title,
                'description' => $validatedData['link'] ?? $aboutUs->description,
                'image' => $imagePath ?? $aboutUs->image
            ]);

            $data = [
                'title' => $aboutUs->title,
                'link' => $aboutUs->description,
                'icon' => $aboutUs->image,
            ];
            return api_response(data: $data, message: 'Updated additional info.');

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
            return api_response(message: 'Successfully deleted social link.');
        } catch (Exception $e) {
            return api_response(message: 'Error in deleted social link', code: 500, errors: [$e->getMessage()]);
        }
    }
}
