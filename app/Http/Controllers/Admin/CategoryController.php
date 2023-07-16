<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Validator;
use Redirect;
use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    
     public function __construct()
     {
        $this->middleware('auth');
     }
     
    
    //TODO - Call Service & Repository From Controller To Implement the Logic -- Future Update

    /**
     * List Categories.
     * Created On : 16-12-2021
     * Author : Aneesh Ajithkumar
     * Email : aneeshajithkumar@hashtagit.online
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $categories = Category::orderBy('created_at','DESC')->paginate(Config('constants.PAGINATION_LIMIT'));
        return view('modules/category/list_categories', compact('categories'));
    }


    /**
     * Create Category.
     * Created On : 17-12-2021
     * Author : Aneesh Ajithkumar
     * Email : aneeshajithkumar@hashtagit.online
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        return view('modules/category/create_category');
    }


    /**
     * Save Category.
     * Created On : 17-12-2021
     * Author : Aneesh Ajithkumar
     * Email : aneeshajithkumar@hashtagit.online
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'name' => 'required|unique:categories',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        } else {
            $category = new Category;
            $category->name = $request->name;
            $category->status = $request->status;
            $category->save();
        
            return redirect(route('categories.index'))->with('message', 'Category Added Successfully.');
        }
    }


    /**
     * Display Category.
     * Created On : 17-12-2021
     * Author : Aneesh Ajithkumar
     * Email : aneeshajithkumar@hashtagit.online
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show($id)
    {
        //TODO
    }


    /**
     * Edit Category.
     * Created On : 17-12-2021
     * Author : Aneesh Ajithkumar
     * Email : aneeshajithkumar@hashtagit.online
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit($id)
    {
        $id = decrypt($id); 
        $category = Category::where('id', $id)->first();
        return view('modules/category/edit_category', compact('category') );
    }


    /**
     * Update Category.
     * Created On : 17-12-2021
     * Author : Aneesh Ajithkumar
     * Email : aneeshajithkumar@hashtagit.online
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),
        [
            'name' => 'required',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        } else {
            $category = Category::find($id);
            $category->name = $request->name;
            $category->status = $request->status;
            $category->save();

            return redirect(route('categories.index'))->with('message', 'Category Edited Successfully!!!!');
        }
    }


    /**
     * Delete Category.
     * Created On : 17-12-2021
     * Author : Aneesh Ajithkumar
     * Email : aneeshajithkumar@hashtagit.online
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function destroy(Request $request)
    {
        $categoryId = $request->categoryId;
        if ($categoryId) {
            $category = Category::where('id', $categoryId)->first();
            if ($category->id) {
                Category::destroy($categoryId);
                $msg = "Success";
            } else {
                $msg = "Error";
            }
        } else {
            $msg = "Error";
        }
        return $msg;
    }
}