<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $post = Post::with('likes')
            ->orderBy('id', 'desc')->get();

        return view('post.index', [
            'post' => $post
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('post.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $post = new Post();

        $post->post = $request->body;
        $post->user_id = Auth::user()->id;

        $post->save();

        return redirect()->route('post.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        if (Auth::user()->id != $post->user_id) {
            abort(403);
        }

        return view('post.edit', [
            'post' => $post
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post, int $id)
    {
        $post = Post::find($id);

        if (Auth::user()->id != $post->user_id) {
            abort(403);
        }
        
        $post->post = $request->post;

        $post->save();


        return redirect()->route('post.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $post = Post::find($id);

        if (Auth::user()->id != $post->user_id) {
            abort(403);
        }


        $post->delete();

        return redirect()->route('post.index');
    }

    public function like(int $id)
    {
        
        $post = Post::find($id);

        
        $post->likes()->attach(Auth::user()->id);

        return response()
            ->json(['success' => 'Like']);
    }

  
    public function dislike(int $id)
    {
        $post = Post::find($id);


        $post->likes()->detach(Auth::user()->id);

        return response()
            ->json(['success' => 'dislike']);
    }
}
