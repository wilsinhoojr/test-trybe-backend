<?php

namespace App\Http\Controllers\Apis\User;

use App\Http\Resources\User\UserResource;
use App\Models\Auth\User;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

/**
 * Class UserController
 * @package App\Http\Controllers\Apis\Auth
 */
class UserController extends Controller
{
    /**
     * @var User
     */
    private $userModel;

    /**
     * UserController constructor.
     * @param User $userModel
     */
    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    /**
     * @return JsonResponse
     */
    public function getUsers()
    {
        try {

            $users = $this->userModel->query()->get();

            return UserResource::collection($users)->response()
                               ->setStatusCode(Response::HTTP_OK, __('messages.success'));
        } catch (Exception $ex) {
            return response()->json(['message' => __('message.error')], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param $userId
     * @return JsonResponse|object
     */
    public function getUser($userId)
    {
        try {

            $user = $this->userModel->query()->find($userId);
            if (empty($user)) {
                return response()->json(['message' => __('messages.user_not_found')])
                                 ->setStatusCode(Response::HTTP_NOT_FOUND);
            }

            return UserResource::make($user)->response()
                               ->setStatusCode(Response::HTTP_OK, __('messages.success'));
        } catch (Exception $ex) {
            return response()->json(['message' => __('message.error')], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @return JsonResponse
     */
    public function me()
    {
        try {

            $valid = auth()->user()->delete();

            if (!$valid) {
                return response()->json(['message' => __('messages.destroy_user_error')], Response::HTTP_UNAUTHORIZED);
            }

            return response()->json([], Response::HTTP_NO_CONTENT);
        } catch (Exception $ex) {
            return response()->json(['message' => __('message.error')], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
