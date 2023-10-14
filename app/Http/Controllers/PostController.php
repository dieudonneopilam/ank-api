<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return PostResource::collection(Post::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $postValidate = $request->validate([
            'title' => ['required'],
            'content' => ['required'],
            'slug' => ['required'],
            'user_id' => ['required', 'numeric'],
            'image' => ['required', 'mimes:png,jpg,jpeg', 'max:4096']
        ]);

        $path = $request->file('image')->store('public/images');

        $post = Post::create([
            'title' => $postValidate['title'],
            'content' => $postValidate['content'],
            'slug' => $postValidate['slug'],
            'user_id' => $postValidate['user_id'],
            'file_path' => $path
        ]);
        return response(['message' => 'post ajouté'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $post = Post::where('id', $id)->first();
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $postValidate = $request->validate([
            'title' => ['required'],
            'content' => ['required'],
            'slug' => ['required'],
            'user_id' => ['required', 'numeric'],
        ]);
        $post = Post::where('id',$id)->first();
        if(!$post) return response(['message' => "aucun post trouvé avec cet id $id"], 404);
        if($post->user_id != $postValidate['user_id']) return response(['message' => 'action refuse'], 404);
        $post->update($postValidate);
        return response(['message' => 'voiture mise a jour'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request ,$id)
    {
        $postValidate = $request->validate([
            'user_id' => ['required', 'numeric'],
        ]);
        $post = Post::where('id',$id)->first();
        if(!$post) return response(['message' => "aucun post trouvé avec cet id $id"], 403);
        if($post->user_id != $postValidate['user_id']) return response(['message' => 'action refuse'], 404);
        $value = Post::destroy($id);
        if (boolval($value) == false) {
            return response(['message' => "aucune voiture trouve avec cet id $id"], 403);
        }
        return response(['message' => "suppression effectuee avec sucess"], 200);
    }
}
