<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\{ PostResource, PostByCategoryResource};
use App\Models\{ Post, Category };
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController as BaseController;
use Exception;

class PostController extends Controller
{
    //TODO - Swagger Integration & Auth Implementation 

    /**
     * List Posts API.
     * Created On : 24-12-2021
     * Author : Aneesh Ajithkumar
     * Email : aneeshajithkumar@hashtagit.online
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $posts = Post::with('categories')
                    ->orderBy('created_at','DESC')
                    ->paginate(Config('constants.PAGINATION_LIMIT'));
                    
        // $response['links'] =  $posts->links;
   
        // return sendResponse($response, 'User register successfully.');
        
        return sendResponse($posts, 'Posts retrieved successfully.');
        //return sendResponse(PostResource::collection($posts), 'Posts retrieved successfully.');
    }

    
    /**
     * Display Single Post.
     * Created On : 24-12-2021
     * Author : Aneesh Ajithkumar
     * Email : aneeshajithkumar@hashtagit.online
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show($id)
    {
        $post = Post::find($id);

        if (is_null($post)) return sendError('Post not found.');

        return sendResponse(new PostResource($post), 'Post retrieved successfully.');
    }


    /**
     * Display Single Post (With Slug As Argument).
     * Created On : 25-12-2021
     * Author : Aneesh Ajithkumar
     * Email : aneeshajithkumar@hashtagit.online
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function postDetails($slug)
    {
        $post = Post::where('slug', $slug)->first();

        if (is_null($post)) return sendError('Post not found.');

        return sendResponse($post, 'Posts retrieved successfully.');

        //return sendResponse(new PostResource($post), 'Post retrieved successfully.');
    }


    /**
     * Display Single Post (With Category As Argument).
     * Created On : 28-12-2021
     * Author : Aneesh Ajithkumar
     * Email : aneeshajithkumar@hashtagit.online
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function postByCategory($categorySlug)
    {
        $categoryId = Category::where('slug', $categorySlug)->pluck('id');
         
        $posts = Post::whereHas('categories', function($query) use($categoryId) {
            $query->where('categories.id', $categoryId);
        })->where('status', 2)->get();

        if (is_null($posts)) return sendError('Post not found.');

        return sendResponse(PostByCategoryResource::collection($posts), 'Posts retrieved successfully.');
    }


    /**
     * Recent Posts.
     * Created On : 29-12-2021
     * Author : Aneesh Ajithkumar
     * Email : aneeshajithkumar@hashtagit.online
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function recentPosts()
    {
        $posts = Post::with('categories')->where('status', 2)->orderBy('created_at','DESC')->take(3)->get();
        
        if (is_null($posts)) return sendError('Recent Posts not found.');

        return sendResponse(PostResource::collection($posts), 'Recent Posts retrieved successfully.');
    }


    /**
     * Related Posts.
     * Created On : 29-12-2021
     * Author : Aneesh Ajithkumar
     * Email : aneeshajithkumar@hashtagit.online
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function relatedPosts($slug)
    {
        $post = Post::with('categories')->where('slug', $slug)->first();
        
        $categoryIDs = $post->categories->pluck('id'); 
        
        $posts = Post::whereHas('categories', function($query) use($categoryIDs) {
            $query->whereIn('categories.id', $categoryIDs);
        })->where('status', 2)->take(2)->where('slug', '!=', $slug)->get();
        
        if (is_null($posts)) return sendError('Related Posts not found.');

        return sendResponse(PostResource::collection($posts), 'Related Posts retrieved successfully.');
    }
}