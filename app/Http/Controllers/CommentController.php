<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return CommentResource::collection(Comment::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $commnentValidate = $request->validate([
            'content' => ['required'],
            'user_id' => ['required', 'numeric'],
            'post_id' => ['required', 'numeric']
        ]);
        $user = User::where('id', $commnentValidate['user_id'])->first();
        $post = Post::where('id', $commnentValidate['post_id'])->first();
        if(!$user) return response(['message' => "aucun utilisateur trouve avec ce id"], 404);
        if(!$post) return response(['message' => "aucun post trouve avec ce id"], 404);
        Comment::create([
            'user_id' => $commnentValidate['user_id'],
            'post_id' => $commnentValidate['post_id'],
            'content' => $commnentValidate['content'],
        ]);
        return response(['message' => 'comment added'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $comment = Comment::where('id', $id)->first();
        if(!$comment) return response(['message' => "aucun comment trouve avec ce id"], 404);
        return new CommentResource($comment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $commnentValidate = $request->validate([
            'content' => ['required'],
            'user_id' => ['required', 'numeric'],
            'post_id' => ['required', 'numeric']
        ]);

        $user = User::where('id', $commnentValidate['user_id'])->first();
        $comment = Comment::where('id', $id)->first();
        if(!$user) return response(['message' => "aucun utilisateur trouve avec ce id"], 404);
        if(!$user) return response(['message' => "aucun post trouve avec ce id"], 404);
        if($comment->user_id != $commnentValidate['user_id']) return response(['message' => 'action refused'], 403);
        $comment->update([
            'content' => $commnentValidate['content']
        ]);
        return response(['message' => 'comment updated'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request ,$id)
    {
        $commentValidate = $request->validate([
            'user_id' => ['required', 'numeric'],
        ]);
        $comment = Comment::where('id',$id)->first();
        if(!$comment) return response(['message' => "aucun comment trouvé avec cet id $id"], 403);
        if($comment->user_id != $commentValidate['user_id']) return response(['message' => 'action refuse'], 404);
        $value = Comment::destroy($id);
        if (boolval($value) == false) {
            return response(['message' => "aucun comment trouve avec cet id $id"], 403);
        }
        return response(['message' => "suppression effectuee avec sucess"], 200);
    }
}
