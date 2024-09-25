<?php

namespace App\Http\Controllers;

use App\Http\Requests\RatingRequest;
use App\Models\Rating;
use App\Models\User;
use App\Services\PaginationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    protected $paginationService;

    public function __construct(PaginationService $paginationService)
    {
        $this->paginationService = $paginationService;
    }

    /**
     * Display a listing of the ratings.
     * @return JsonResponse
     * @author Khaled <khaledabdullah2001104@gmail.com>
     */
    public function index(Request $request)
    {
        $query = User::query()->with('driver_ratings');

        $driversRatings = $this->paginationService->applyPagination($query, $request);
        return api_response(data: $driversRatings, message: 'Successfully getting drivers ratings.', pagination: get_pagination($driversRatings, $request));
    }

    /**
     * Store a newly ratings for driver
     * @param RatingRequest $request
     * @return JsonResponse
     * @author Khaled <khaledabdullah2001104@gmail.com>
     *
     */
    public function store(RatingRequest $request)
    {
        try {

            $validatedData = $request->validated();

            $rating = Rating::create($validatedData);
            $driver = getAndCheckModelById(User::class, $rating->driver_id);
            $this->updateDriverRating($driver);
            return api_response(message: 'Successfully getting ratings.');
        } catch (\Exception $e) {
            return api_response(errors: [$e->getMessage()], message: 'Something went wrong, please try again later.', code: 500);
        }
    }

    /**
     * Update driver rating
     * @param User $driver
     * @return void
     * @author Khaled <khaledabdullah2001104@gmail.com>
     *
     */
    protected function updateDriverRating(User $driver)
    {
        $totalRatings = $driver->driver_ratings()->sum('rating');
        $countRatings = $driver->driver_ratings()->count();
        $driver->rating = $totalRatings / $countRatings;
        $driver->save();
    }
}
