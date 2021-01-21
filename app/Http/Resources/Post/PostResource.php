<?php

namespace App\Http\Resources\Post;

use App\Models\Post\Post;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * @var Post
     */
    private Post $post;

    /**
     * PostResource constructor.
     * @param Post $post
     * @param null $resource
     */
    public function __construct(Post $post, $resource = null)
    {
        parent::__construct($resource);
        $this->post = $post;
    }

    /**
     * Transform the resource into an array.
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'        => $this->post->id,
            'title'     => $this->post->title,
            'content'   => $this->post->content,
            'published' => $this->post->published,
            'updated'   => $this->post->updated_at,
            'user'      => [
                'id'          => $this->post->user->id,
                'displayName' => $this->post->user->displayName,
                'email'       => $this->post->user->email,
                'image'       => $this->post->user->image,
            ],
        ];
    }
}
