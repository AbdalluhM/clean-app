<?php

namespace App\Http\Controllers\Wep;

use App\Models\Category;
use App\Models\SupCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\SupCategoryRequest;

class ServiceController extends Controller
{
    // function __construct()
    // {
    //     $this->middleware('permission:SupCategory-list|SupCategory-create|SupCategory-edit|SupCategory-delete', ['only' => ['index', 'store', 'sub_SupCategory']]);
    //     $this->middleware('permission:SupCategory-create', ['only' => ['create', 'store']]);
    //     $this->middleware('permission:SupCategory-edit', ['only' => ['edit', 'update']]);
    //     $this->middleware('permission:SupCategory-delete', ['only' => ['destroy']]);
    // }
    public function index()
    {
        $services =  SupCategory::paginate(4);
        return view('dashboard.services.index')->with('services', $services);
    }

    public function create()
    {
        $categories = Category::all();
        return view('dashboard.services.add')->with('categories', $categories);
    }


    public function store(SupCategoryRequest $request)
    {
        $image = time() . '_' . $request->file('image')->hashName();
        $request->file('image')->storeAs('public/images/services/', $image);
        SupCategory::create(array_merge($request->all(), ['image' => $image]));
        Toastr::success('Service added successfully :)', 'Success');
        return redirect()->back();
    }
    public function edit(SupCategory $service)
    {
        // dd($service->supcategory_image_path);
        $categories = Category::all();
        $category = $service->category;
        // dd($category);
        return view('dashboard.services.update')->with(['service' => $service, 'categories' => $categories, 'categorySelect' => $category]);
    }



    public function update(Request $request, SupCategory $service)
    {
        // dd($service);

        $input = $request->validate([
            'name_ar' => 'required',
            'name_en' => 'required',
            'desc' => 'required',
            'image' => 'mimes:png,jpg',
            'category_id' => 'required',
        ]);
        if (request()->hasFile('image')) {

            Storage::disk('public')->delete('/images/services/' . $service->image);
            $image = time() . '_' . $request->file('image')->hashName();
            $request->file('image')->storeAs('public/images/services/', $image);
            $input['image'] = $image;
        }

        $service->update($input);
        Toastr::success('Service Updated successfully :)', 'Success');
        return redirect()->route('services.index');
    }

    public function destroy(SupCategory $SupCategory)
    {
        Storage::disk('public')->delete('/images/services/' . $SupCategory->image);
        $SupCategory->delete();
        Toastr::success('Service Deleted successfully :)', 'Success');
        return redirect(route('services.index'));
    }


    public function search(Request $request)
    {
        $SupCategory_name = trim($request->searchName);
        $SupCategory = SupCategory::where('SupCategory_name', 'like', "%{$SupCategory_name}%")->paginate(5);
        return view('dashboard.services.index')->with('services', $SupCategory);
    }
}
