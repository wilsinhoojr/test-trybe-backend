<?php

namespace App\Http\Resources\Post;

use App\Models\Post\Post;
use Illuminate\Http\Resources\Json\JsonResource;

class PostUpdatedResource extends JsonResource
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
            'title'   => $this->post->title,
            'content' => $this->post->content,
            'user_id' => $this->post->user_id,
        ];
    }
}
