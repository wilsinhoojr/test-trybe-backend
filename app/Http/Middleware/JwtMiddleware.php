<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws \Tymon\JWTAuth\Exceptions\JWTException
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof TokenExpiredException || $e instanceof TokenInvalidException) {
                return response()->json(['status' => __('auth.token_expired_or_invalid')], Response::HTTP_UNAUTHORIZED);
            } else {

                return response()->json(['status' => __('auth.token_not_found')], Response::HTTP_UNAUTHORIZED);
            }
        }

        return $next($request);
    }
}
