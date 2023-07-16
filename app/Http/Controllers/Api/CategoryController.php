<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class CategoryController extends Controller
{
    //TODO - Swagger Integration & Auth Implementation In POSTS -- Future Update

    /**
     * List Categories API.
     * Created On : 24-12-2021
     * Author : Aneesh Ajithkumar
     * Email : aneeshajithkumar@hashtagit.online
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $categories = Category::where('status', 1)->orderBy('created_at','DESC')->get();
        return sendResponse(CategoryResource::collection($categories), 'Category retrieved successfully.');
    }

    
    /**
     * Display Single Category.
     * Created On : 24-12-2021
     * Author : Aneesh Ajithkumar
     * Email : aneeshajithkumar@hashtagit.online
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show($id)
    {
        $category = Category::find($id);

        if (is_null($post)) return sendError('Category not found.');

        return sendResponse(new CategoryResource($category), 'Category retrieved successfully.');
    }


    /**
     * Category List With Post Count.
     * Created On : 29-12-2021
     * Author : Aneesh Ajithkumar
     * Email : aneeshajithkumar@hashtagit.online
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function categoryPostCounts()
    {
       $categories = Category::withCount('posts')->where('status', 1)->get();
       return sendResponse(CategoryResource::collection($categories), 'Category retrieved successfully.');
    }
}