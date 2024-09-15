<?php

namespace App\Http\Controllers;

use App\Http\Requests\AboutUsRequest;
use App\Models\AboutUs;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

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
        try {
            $aboutUsRecords = AboutUs::select('id', 'title', 'description', 'complaints_number', 'image');

            if (request()->wantsJson()) {
                return api_response(data: $aboutUsRecords, message: 'Successfully retrieved about us.');
            }

            $additional_info = AboutUs::select('id', 'title', 'description', 'image')->where('is_general', false)->get();

            return view('aboutus.index', ['aboutUsRecords' => $aboutUsRecords, 'additional_info' => $additional_info]);

        } catch (Exception $e) {
            if (request()->wantsJson()) {
                return api_response(errors: [$e->getMessage()], message: 'نجحنا في الحصول على التفاصيل عنا', code: 500);
            }

            return redirect()->back()->withErrors('هنالك خطأ في جلب البيانات الرجاء المحاولة مرة أخرى.\nالاخطاء:\n errors:' . $e->getMessage())->withInput();
        }
    }


    /**
     * Get the additional information
     * @return JsonResponse
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-30
     */
    public function getAdditionInformation()
    {
        try {
            $data = AboutUs::select('id', 'title', 'description', 'image')->where('is_general', false)->get();

            return api_response(data: $data, message: 'Successfully retrieved additional about us information.');
        } catch (Exception $e) {
            return api_response(errors: $e->getMessage(), message: 'Error in retrieved additional about us information.', code: 500);
        }
    }

    /**
     * Store or update the general about us details
     * @param AboutUsRequest $request
     * @return \Illuminate\Http\RedirectResponse to view page
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-30
     */
    public function create()
    {
        return view('aboutus.create');
    }

    /**
     * Store or update the general about us details
     * @param AboutUsRequest $request
     * @return \Illuminate\Http\RedirectResponse to view page
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-30
     */
    public function edit(AboutUs $aboutUs)
    {
        return view('aboutus.edit', ['aboutUs' => $aboutUs]);
    }

    /**
     * Store or update the general about us details
     * @param AboutUsRequest $request
     * @return \Illuminate\Http\RedirectResponse to view page
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

                $messag = 'Successfully created about us.';
            } else {
                $imagePath = $validatedData['image'] ? editFile($aboutUs->image, '/images/aboutUs', $validatedData['image']) : $aboutUs->image;
                $aboutUs->update([
                    'title' => $validatedData['title'],
                    'description' => $validatedData['description'],
                    'complaints_number' => $validatedData['complaints_number'],
                    'image' => $imagePath
                ]);
                $messag = 'Successfully updated about us.';
            }
            return redirect()->route('aboutus.index')->with('success', $messag);
        } catch (Exception $e) {
            return redirect()->back()->withErrors('There error in proceced data.\n errors:' . $e->getMessage())->withInput();
        }
    }

    /**
     * Create additional info records
     * @param AboutUsRequest $request
     * @return \Illuminate\Http\RedirectResponse to view page
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-
     */
    public function create_additional_info()
    {
        // يمكنك تعيين البيانات التي تحتاجها في صفحة الإنشاء هنا
        return view('aboutus.create_additional_info');
    }

    /**
     * Create additional info records
     * @param AboutUsRequest $request
     * @return \Illuminate\Http\RedirectResponse to view page
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-
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

            return redirect()->route('aboutus.index')->with('success', 'Successfully created additional info.');

        } catch (Exception $e) {
            return redirect()->back()->withErrors('Error in create additional info\n errors:' . $e->getMessage())->withInput();
        }
    }

    /**
     * Update additional info records
     * @param AboutUsRequest $request
     * @param AboutUs $aboutUs
     * @return \Illuminate\Http\RedirectResponse to view page
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-
     */
    public function edit_additional_info(AboutUs $aboutUs)
    {
        return view('aboutus.edit', ['aboutUs' => $aboutUs]);
    }

    /**
     * Update additional info records
     * @param AboutUsRequest $request
     * @param AboutUs $aboutUs
     * @return \Illuminate\Http\RedirectResponse to view page
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-
     */
    public function updateAdditionalInfo(AboutUsRequest $request, AboutUs $aboutUs)
    {
        try {

            $validatedData = $request->validated();
            $imagePath = $validatedData['image'] ? editFile($aboutUs->image, '/images/aboutUs/additional', $validatedData['image']) : $aboutUs->image;
            $aboutUs->update([
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
                'image' => $imagePath
            ]);
            return redirect()->route('aboutus.index')->with('success', 'Updated additional info.');

        } catch (Exception $e) {
            return redirect()->back()->withErrors('Error in update additional info.\n errors:' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param AboutUs $aboutUs
     * @return \Illuminate\Http\RedirectResponse to view page
     * @author Khaled <khaledabdullah2001104@gmail.com>
     * @Target T-
     */
    public function destroy(AboutUs $aboutUs)
    {
        try {
            $message = removeFile($aboutUs->image);
            if ($message == 'failed')
                return redirect()->back()->withErrors('')->withInput();

            $aboutUs->delete();
            return redirect()->route('aboutus.index')->with('success', 'Successfully deleted about us.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors('Error in deleted about us\n errors:' . $e->getMessage())->withInput();
        }
    }
}
