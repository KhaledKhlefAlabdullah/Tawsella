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
        if ($user && $user->hasRole('Admin')) {
            $invalidAdvertisements = Advertisement::query()->where('validity_date', '<', date('Y-m-d'));
            $invalidAdvertisements = $this->paginationService->applyPagination($invalidAdvertisements, $request);
            return api_response(data: ['validAdvertisements' => $validAdvertisements->items(), 'invalidAdvertisements' => $invalidAdvertisements->items()], message: 'تم جلب الإعلانات بالنجاح', pagination: get_pagination($validAdvertisements, $request));
        }

        return api_response(data: $validAdvertisements->items(), message: 'تم جلب الاعلانات بنجاح', pagination: get_pagination($validAdvertisements, $request));
    }

    /**
     * Get advertisements details
     * @param Advertisement $advertisement
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Advertisement $advertisement){
        return api_response(data: $advertisement, message: 'تم جلب تفاصيل الإعلان بنجاح');
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

            $imagePath = array_key_exists('image',$validatedData) ? storeFile($validatedData['image'], '/images/advertisements/images') : '/images/services/images/service.png';
            $logoPath = array_key_exists( 'logo',$validatedData) ? storeFile($validatedData['logo'], '/images/advertisements/logos') : '/images/services/logos/logo.png';

            $Advertisement = Advertisement::create([
                'admin_id' => $validatedData['admin_id'],
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
                'image' => $imagePath,
                'logo' => $logoPath,
                'validity_date' => $validatedData['validity_date'],
            ]);

            return api_response(data: $Advertisement, message: 'تم إضافة الإعلان بنجاح');
        } catch (Exception $e) {
            return api_response(message: 'خطأ في إضافة الإعلان', code: 500, errors: [$e->getMessage()]);
        }
    }

    /**
     * Update an existing service.
     * @param AdvertisementsRequest $request Updated service data
     * @param Advertisement $advertisement Service to update
     * @return JsonResponse redirect back response
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-17
     */
    public function update(AdvertisementsRequest $request, Advertisement $advertisement)
    {
        try {
            $validatedData = $request->validated();

            $imagePath = array_key_exists('image',$validatedData) ? editFile($advertisement->image, '/images/advertisements/images', $validatedData['image']) : $advertisement->image;
            $logoPath = array_key_exists( 'logo',$validatedData) ? editFile($advertisement->logo, '/images/advertisements/logos', $validatedData['logo']) : $advertisement->logo;

            $advertisement->update([
                'admin_id' => $validatedData['admin_id'],
                'title' => $validatedData['title'] ?? $advertisement->title,
                'description' => $validatedData['description'] ?? $advertisement->description,
                'image' => $imagePath,
                'logo' => $logoPath
            ]);

            return api_response(data: $advertisement, message: 'تم تعديل الاعلان بنجاح');
        } catch (Exception $e) {
            return api_response(message: 'خطأ في تعديل الإعلان', code: 500, errors: [$e->getMessage()]);
        }
    }

    /**
     * Delete a service from the database.
     * @param Advertisement $advertisement Service to delete
     * @return JsonResponse API response indicating success or failure
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-18
     */
    public function destroy(Advertisement $advertisement)
    {
        try {

            removeFile($advertisement->logo);
            removeFile($advertisement->image);
            $advertisement->delete();

            return api_response(message: 'تم حذف الإعلان بنجاح');
        } catch (Exception $e) {
            return api_response(message: 'خطأ في حذف الإعلان', code: 500, errors: [$e->getMessage()]);
        }
    }
}
