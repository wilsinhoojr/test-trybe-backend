<?php

namespace App\Http\Controllers\Apis\Auth;

use App\Services\AuthService;
use Exception;
use App\Models\Auth\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AuthController
 * @package App\Http\Controllers\Apis\Auth
 */
class AuthController extends Controller
{
    /**
     * @var User
     */
    private $userModel;
    /**
     * @var AuthService
     */
    private $authService;

    /**
     * AuthController constructor.
     * @param User $userModel
     * @param $authService
     */
    public function __construct(User $userModel, AuthService $authService)
    {
        $this->userModel   = $userModel;
        $this->authService = $authService;
    }

    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function user(RegisterRequest $request)
    {
        try {
            $data = $request->validated();
            /** @var User $user */
            $user = $this->userModel->query()->create([
                                                          'displayName' => $data['displayName'],
                                                          'email'       => $data['email'],
                                                          'password'    => Hash::make($data['password']),
                                                          'image'       => $data['image'],
                                                      ]);
            if ($user) {
                $credentials = $request->only('email', 'password');

                if ($token = $this->authService->guard()->attempt($credentials)) {
                    return $this->authService->respondWithToken($token);
                }

                return response()->json(['error' => 'Unauthorized'], \Illuminate\Http\Response::HTTP_UNAUTHORIZED);
            }

            return response()->json(['message' => __('auth.register_error')], Response::HTTP_BAD_REQUEST);
        } catch (Exception $ex) {
            return response()->json(['message' => __('message.error')], \Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request)
    {
        try {

            $credentials = $request->only('email', 'password');

            if ($token = $this->authService->guard()->attempt($credentials)) {
                return $this->authService->respondWithToken($token);
            }

            return response()->json(['message' => __('auth.failed_trybe')], \Illuminate\Http\Response::HTTP_UNAUTHORIZED);
        } catch (Exception $ex) {
            return response()->json(['message' => __('message.error')], \Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @return JsonResponse
     */
    public function logout()
    {
        try {

            auth()->logout();

            return response()->json(null, Response::HTTP_OK);
        } catch (Exception $ex) {
            return response()->json(['message' => __('message.error')], \Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
