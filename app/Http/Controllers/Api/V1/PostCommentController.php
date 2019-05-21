<?php

namespace App\Http\Controllers\Api\V1;

use App\Post;
use Illuminate\Http\Request;
use App\Events\CommentPosted;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\CommentsRequest;
use App\Http\Resources\Comment as CommentResource;

class PostCommentController extends Controller
{
    /**
     * Return the post's comments.
     *
     * @param  Request $request
     * @param  Post $post
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Post $post)
    {
        return CommentResource::collection(
            $post->comments()->with('author')->latest()->paginate($request->input('limit', 20))
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CommentsRequest $request
     * @param  Post $post
     * @return \Illuminate\Http\Response
     */
    public function store(CommentsRequest $request, Post $post)
    {
        $comment = new CommentResource(
            Auth::user()->comments()->create([
                'post_id' => $post->id,
                'content' => $request->input('content'),
            ])
        );

        broadcast(new CommentPosted($comment, $post))->toOthers();

        return $comment;
    }
}
