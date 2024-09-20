<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServicesRequest;
use App\Models\OurService;
use Exception;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use function PHPUnit\Framework\isJson;

class OurServiceController extends Controller
{
    /**
     * Display all services.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application response containing all services data
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-15
     */
    public function index()
    {
        // Fetch all records from OurService model
        $services = OurService::select('id', 'name', 'description', 'image', 'logo', 'created_at')->get();

        if(request()->wantsJson()) {
            api_response(data: $services, message: 'Successfully getting messages');
        }

        return view('services', $services);
    }

    /**
     * Store a new service in the database.
     * @param ServicesRequest $request Service data from request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application redirect to create view
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-16
     */
    public function create()
    {
        return view('service.create');
    }

    /**
     * Store a new service in the database.
     * @param ServicesRequest $request Service data from request
     * @return \Illuminate\Http\RedirectResponse response indicating success or failure
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-16
     */
    public function store(ServicesRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $imagePath = $validatedData['image'] ? storeFile($validatedData['image'], '/images/services') : '/images/services/images/service.jpg';

            $logoPath = $validatedData['logo'] ? storeFile($validatedData['logo'], '/images/logos') : '/images/services/logos/logo.jpg';

            OurService::create([
                'admin_id' => $validatedData['admin_id'],
                'name' => $validatedData['name'],
                'description' => $validatedData['description'],
                'image' => $imagePath,
                'logo' => $logoPath
            ]);

            return redirect()->route('services')->with('success', __('service-created-success'));
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            return redirect()->back()->withErrors(__('service-created-error')."\n errors:" . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param OurService $service
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function edit(OurService $service)
    {
        return view('service.edit', ['service' => $service]);
    }

    /**
     * Update an existing service.
     * @param ServicesRequest $request Updated service data
     * @param OurService $service Service to update
     * @return \Illuminate\Http\RedirectResponse redirect back response
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

            return redirect()->route('services')->with('success', __('service-edited-success'));
        } catch (Exception $e) {
            return redirect()->back()->withErrors(__('service-edited-error') . "\n errors:" . $e->getMessage())->withInput();
        }
    }

    /**
     * Delete a service from the database.
     * @param OurService $service Service to delete
     * @return \Illuminate\Http\RedirectResponse API response indicating success or failure
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-18
     */
    public function destroy(OurService $service)
    {
        try {

            removeFile($service->logo);
            removeFile($service->image);
            $service->delete();

            return redirect()->route('services')->with('success', __('movement-type-delete-success'));
        } catch (Exception $e) {
            return redirect()->back()->withErrors(__('movement-type-delete-error') . "\n errors:" . $e->getMessage());
        }
    }
}
