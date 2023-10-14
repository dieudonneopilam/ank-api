<?php

namespace App\Http\Resources;

use App\Http\Resources\CommentResource;
use App\Http\Resources\UserResource;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
     public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'slug' => $this->slug,
            'pathImage' => $this->file_path,
            'user' => new UserResource($this->user),
            'comments' => CommentResource::collection(new CommentResource($this->comments)),
            'datePost' => $this->created_at,
            'dateUpdate' => $this->updated_at,
        ];
    }
}
