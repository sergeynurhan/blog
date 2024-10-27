<?php

namespace App\Http\Controllers\api;

use App\Models\Comment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommentControllerApi extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comments = Comment::all();

        return response()->json($comments);
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
            "user" => "required",
            "content" => "required"
        ]);

        $input = $request->all();
        $comment = Comment::create($input);

        return response()->json($comment);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        return response()->json($comment);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        return response()->json($comment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        $request->validate([
            "user" => "required",
            "content" => "required"
        ]);

        if ($comment->user != $request->user()->id) {
            return response()->json("you can't update this comment");
        }

        $comment->update($request->all());

        return response()->json($comment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment, Request $request)
    {
        if ($comment->user != $request->user()->id) {
            return response()->json("you can't update this comment");
        }
        
        $comment->delete();
        return response()->json("comment was deleted");
    }
}
