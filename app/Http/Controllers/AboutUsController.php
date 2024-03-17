<?php

namespace App\Http\Controllers;

use App\Models\AboutUs;
use Exception;
use Illuminate\Http\Request;

class AboutUsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            
            return api_response(message:'get-aboutus-success');
        }
        catch(Exception $e){
            return api_response(message:'get-aboutus-success');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(AboutUs $aboutUs)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AboutUs $aboutUs)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AboutUs $aboutUs)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AboutUs $aboutUs)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\AboutUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AboutUsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {


        $aboutUsRecords = AboutUs::all();
        return view('aboutus.index', ['aboutUsRecords' => $aboutUsRecords]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // يمكنك تعيين البيانات التي تحتاجها في صفحة الإنشاء هنا
        return view('aboutus.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'complaints_number' => 'required|numeric',
        ]);

        $data['admin_id'] = Auth::id();

        AboutUs::create($data);

        return redirect()->route('aboutus.index')->with('success', 'تم إنشاء نبذة عنا بنجاح.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AboutUs $aboutUs)
    {
        // يمكنك تعيين البيانات التي تريد عرضها في صفحة العرض هنا
        return view('aboutus.show', ['aboutUs' => $aboutUs]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AboutUs $aboutUs)
    {
        // يمكنك تعيين البيانات التي تحتاجها في صفحة التحرير هنا
        return view('aboutus.edit', ['aboutUs' => $aboutUs]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AboutUs $aboutUs)
    {
        $data = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'complaints_number' => 'required|numeric',
        ]);

        $aboutUs->update($data);

        return redirect()->route('aboutus.index')->with('success', 'تم تحديث نبذة عنا بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AboutUs $aboutUs)
    {
        $aboutUs->delete();

        return redirect()->route('aboutus.index')->with('success', 'تم حذف نبذة عنا بنجاح.');
    }
}
