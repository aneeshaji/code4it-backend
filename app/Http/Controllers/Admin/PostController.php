<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Validator;
use Redirect;
use App\Http\Controllers\Controller;
use App\Models\{ Post, Category};

class PostController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    //TODO - Call Service & Repository From Controller To Implement the Logic -- Future Update

    /**
     * List Posts.
     * Created On : 16-12-2021
     * Author : Aneesh Ajithkumar
     * Email : aneeshajithkumar@hashtagit.online
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $posts = Post::with('categories')->orderBy('created_at','DESC')->paginate(Config('constants.PAGINATION_LIMIT'));
        return view('modules/posts/list_posts', compact('posts'));
    }


    /**
     * Create Post.
     * Created On : 22-12-2021
     * Author : Aneesh Ajithkumar
     * Email : aneeshajithkumar@hashtagit.online
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        $categories = Category::get();
        return view('modules/posts/create_post', compact('categories'));
    }


    /**
     * Save Post.
     * Created On : 22-12-2021
     * Author : Aneesh Ajithkumar
     * Email : aneeshajithkumar@hashtagit.online
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'title' => 'required|unique:posts',
            'categories' => 'required',
            'body' => 'required',
            'status' => 'required',
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        } else {
            //Handle file upload 
            //TODO - Implement S3 Bucket For Upload & Add Helper function for file operations.
            if ($request->hasFile('image')) {
                // Get filename with the extension
                $filenameWithExt = $request->file('image')->getClientOriginalName();
                // Get just filename
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                // Get just ext
                $extension = $request->file('image')->getClientOriginalExtension();
                // Filename to store
                $fileNameToStore = $filename.'_'.time().'.'.$extension;
                // Upload image
                //$path = $request->file('image')->storeAs('public/images', $fileNameToStore);
                $path = $request->file('image')->move(public_path('uploads/posts'), $fileNameToStore);
            } else {
                $fileNameToStore = 'noimage.jpg';
            }

            //Create new post
            $post = new Post;
            $post->image = $fileNameToStore;
            $post->title = $request->title;
            //$post->subtitle = $request->subtitle;
            //$post->slug = $request->slug;
            $post->body = $request->body;
            $post->status = $request->status;
            $post->save();
            
            // Many to many relation between Posts and Tags
            //$post->tags()->sync($request->tags);
            
            // Many to many relation between Posts and Categories
            $post->categories()->sync($request->categories);

            return redirect(route('posts.index'))->with('message', 'Post Added Successfully.');
        }
    }


     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //TODO
    }


    /**
     * Edit Post.
     * Created On : 23-12-2021
     * Author : Aneesh Ajithkumar
     * Email : aneeshajithkumar@hashtagit.online
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit($id)
    {
        $id = decrypt($id);
        $post = Post::with('categories')->where('id', $id)->first();
        $categories = Category::all();
        return view('modules/posts/edit_post', compact('post', 'categories'));
    }


    /**
     * Update Post.
     * Created On : 23-12-2021
     * Author : Aneesh Ajithkumar
     * Email : aneeshajithkumar@hashtagit.online
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),
        [
            'title' => 'required',
            'categories' => 'required',
            'body' => 'required',
            'status' => 'required',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        } else {
            $post = Post::find($id);
            //Handle file upload 
            //TODO - Implement S3 Bucket For Upload & Add Helper function for file operations.
            if ($request->hasFile('image')) {
                // Get filename with the extension
                $filenameWithExt = $request->file('image')->getClientOriginalName();
                // Get just filename
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                // Get just ext
                $extension = $request->file('image')->getClientOriginalExtension();
                // Filename to store
                $fileNameToStore = $filename.'_'.time().'.'.$extension;
                // Upload image
                //$path = $request->file('image')->storeAs('public/images', $fileNameToStore);
                $path = $request->file('image')->move(public_path('uploads/posts'), $fileNameToStore);
                $post->image = $fileNameToStore;
            } 

            //Update post
            $post->title = $request->title;
            //$post->subtitle = $request->subtitle;
            //$post->slug = $request->slug;
            $post->body = $request->body;
            $post->status = $request->status;
            $post->save();
            
            // Many to many relation between Posts and Tags
            //$post->tags()->sync($request->tags);
            
            // Many to many relation between Posts and Categories
            $post->categories()->sync($request->categories);

            return redirect(route('posts.index'))->with('message', 'Post Updated Successfully.');
        }
    }


    /**
     * Delete Post.
     * Created On : 23-12-2021
     * Author : Aneesh Ajithkumar
     * Email : aneeshajithkumar@hashtagit.online
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function destroy(Request $request)
    {
        $postId = $request->postId;
        if ($postId) {
            $post = Post::where('id', $postId)->first();
            if ($post->id) {
                Post::destroy($postId);
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
