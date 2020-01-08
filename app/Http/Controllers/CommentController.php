<?php

namespace App\Http\Controllers;
use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function countComments()
    {
        
    }

    public function store(Request $request)
    {
        $this->validate($request,[ 'comment' => 'required' ],
        [
            'comment.required' => 'Comment cannot be empty',
        ]);

        $comment = new Comment();
        
        $comment->comment = $request->comment;
        $comment->post_id = $request->post_id;
        $comment->user_id = Auth::user()->id;
        $comment->save();

        return redirect()->route('post.index');
    }

    public function messages()
    {
        return [
            'comment.required' => 'please fill the comment box',
        ];
    }
}
