<?php

namespace App\Http\Resources\User;

use App\Models\Auth\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * @var User
     */
    private User $user;

    /**
     * UserResource constructor.
     * @param User $user
     * @param null $resource
     */
    public function __construct(User $user, $resource = null)
    {
        parent::__construct($resource);
        $this->user = $user;
    }

    /**
     * Transform the resource into an array.
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'          => $this->user->id,
            'displayName' => $this->user->displayName,
            'email'       => $this->user->email,
            'image'       => $this->user->image,
        ];
    }
}
