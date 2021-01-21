<?php

namespace App\Services;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * Class AuthService
 * @package App\Services
 */
class AuthService
{
    /**
     * @param $token
     * @return JsonResponse
     */
    public function respondWithToken($token)
    {
        return response()->json([
                                    'access_token' => $token,
                                    'token_type'   => 'bearer',
                                    'expires_in'   => $this->guard()->factory()->getTTL() * 60,
                                ]);
    }

    /**
     * @return Guard|StatefulGuard
     */
    public function guard()
    {
        return Auth::guard();
    }
}
