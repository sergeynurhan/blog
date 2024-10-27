<?php

namespace App\Http\Controllers\api;

use Carbon\Carbon;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Comment;

class PostControllerApi extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $posts = Post::with(['author', 'comments', 'tags'])->get();

        return response()->json([
            'posts' => $posts
        ]);
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
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'tags' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $input = $request->except('tags');
        $input['user'] = $request->user()->id;
        $input['posted_at'] = Carbon::now();

        if ($image = $request->file('image')) {
            $destinationPath = 'images/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = "$profileImage";
        }

        $post = Post::create($input);

        if (!empty($request->tags)) {
            $post->tags()->attach($request->tags);
        }

        return response()->json($post);
    }

    /**

     * Display the specified resource.

     */

    public function show(Post $post)
    {
        $post->comments = Comment::where('post_id', $post->id)->get();
        $post->tags = $post->tags()->get();

        return response()->json($post);
    }

    /**

     * Show the form for editing the specified resource.

     */

    public function edit(Post $post)
    {
        return response()->json($post);
    }

    /**

     * Update the specified resource in storage.

     */

    public function update(Request $request, Post $post)
    {
        if ($post->user != $request->user()->id) {
            return response()->json("you can't update this post");
        }

        $request->validate([
            'user' => 'required',
            'title' => 'required',
            'description' => 'required',
            'tags' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $input = $request->except('tags');

        if ($image = $request->file('image')) {
            $destinationPath = 'images/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = "$profileImage";
        } else {
            unset($input['image']);
        }

        if (!empty($request->tags)) {
            $post->tags()->sync($request->tags);
        }

        $post->update($input);
        return response()->json($post);
    }

    /**

     * Remove the specified resource from storage.

     */

    public function destroy(Post $post, Request $request)
    {
        if ($post->user != $request->user()->id) {
            return response()->json("you can't delete this post");
        }

        $post->delete();
        // return response()->json("post was deleted");
        return response()->json($request->user()->id);
    }
}
