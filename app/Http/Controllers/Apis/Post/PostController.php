<?php

namespace App\Http\Controllers\Apis\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Http\Resources\Post\PostCreatedResource;
use App\Http\Resources\Post\PostResource;
use App\Http\Resources\Post\PostUpdatedResource;
use App\Models\Post\Post;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class PostController
 * @package App\Http\Controllers\Post
 */
class PostController extends Controller
{
    /**
     * @var Post
     */
    private $postModel;

    /**
     * PostController constructor.
     * @param Post $postModel
     */
    public function __construct(Post $postModel)
    {
        $this->postModel = $postModel;
    }

    public function index()
    {
        try {
            $posts = $this->postModel->query()->get();

            return PostResource::collection($posts)->response()
                               ->setStatusCode(Response::HTTP_OK, __('messages.success'));
        } catch (Exception $ex) {
            return response()->json(['message' => __('message.error')], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param StorePostRequest $request
     * @return JsonResponse|object
     */
    public function store(StorePostRequest $request)
    {
        try {

            $data = $request->validated();
            /** @var Post $post */
            $post = $this->postModel->query()->create([
                                                          'title'     => $data['title'],
                                                          'user_id'   => auth()->user()->id,
                                                          'content'   => $data['content'],
                                                          'published' => now(),
                                                      ]);
            if ($post) {
                return PostCreatedResource::make($post)->response()
                                          ->setStatusCode(Response::HTTP_CREATED, __('messages.success'));
            }

            return response()->json(['message' => __('message.error')], Response::HTTP_BAD_REQUEST);
        } catch (Exception $ex) {
            return response()->json(['message' => __('message.error')], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($postId)
    {
        try {

            $post = $this->postModel->query()->find($postId);
            if (!$post) {
                return response()->json(['message' => __('messages.post_not_found')])
                                 ->setStatusCode(Response::HTTP_NOT_FOUND);
            }

            return PostResource::make($post)->response()
                               ->setStatusCode(Response::HTTP_OK, __('messages.success'));
        } catch (Exception $ex) {
            return response()->json(['message' => __('message.error')], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param $postId
     * @param UpdatePostRequest $request
     * @return JsonResponse|object
     */
    public function update($postId, UpdatePostRequest $request)
    {
        try {

            /** @var Post $post */
            $post = $this->postModel->query()->find($postId);
            if (!$post) {
                return response()->json(['message' => __('messages.post_not_found')])
                                 ->setStatusCode(Response::HTTP_NOT_FOUND);
            }
            if ($post->user_id != auth()->user()->id) {
                return response()->json(['message' => __('messages.unauthorized_user')])
                                 ->setStatusCode(Response::HTTP_UNAUTHORIZED);
            }
            $data = $request->validated();
            $valid = $post->update([
                                       'title'   => $data['title'],
                                       'content' => $data['content'],
                                   ]);
            if ($valid) {
                return PostUpdatedResource::make($post)->response()
                                          ->setStatusCode(Response::HTTP_CREATED, __('messages.success'));
            }

            return response()->json(['message' => __('message.error')], Response::HTTP_BAD_REQUEST);
        } catch (Exception $ex) {
            return response()->json(['message' => __('message.error')], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse|object
     */
    public function search(Request $request)
    {
        try {

            $data = $request->all();

            if (!isset($data['q']) || is_null($data['q'])) {
                return $this->index();
            }
            $posts = $this->postModel->query()->where('title', 'like', '%' . $data['q'] . '%')
                                     ->orWhere('content', 'like', '%' . $data['q'] . '%')->get();

            return PostResource::collection($posts)->response()
                               ->setStatusCode(Response::HTTP_OK, __('messages.success'));
        } catch (Exception $ex) {
            return response()->json(['message' => __('message.error')], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($postId)
    {
        try {
            /** @var Post $post */
            $post = $this->postModel->query()->find($postId);
            if (!$post) {
                return response()->json(['message' => __('messages.post_not_found')])
                                 ->setStatusCode(Response::HTTP_NOT_FOUND);
            }
            if ($post->user_id != auth()->user()->id) {
                return response()->json(['message' => __('messages.unauthorized_user')])
                                 ->setStatusCode(Response::HTTP_UNAUTHORIZED);
            }
            $valid = $post->delete();
            if ($valid) {
                return response()->json(['message' => __('message.success')], Response::HTTP_NO_CONTENT);
            }

            return response()->json(['message' => __('message.error')], Response::HTTP_BAD_REQUEST);
        } catch (Exception $ex) {
            return response()->json(['message' => __('message.error')], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
