<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    /**
     * 處理用戶登入請求
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {

        // 驗證請求數據
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // 手動驗證用戶
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => 'error',
                    'error' => '提供的憑證不正確'
                ], 401);
            }

            // 直接使用 JWTAuth 生成 token
            $token = JWTAuth::fromUser($user);

            // 登入成功，返回JWT令牌
            return response()->json([
                'status' => 'success',
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => config('jwt.ttl') * 60 // 使用配置中的 TTL
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'status' => 'error',
                'error' => '無法創建令牌'
            ], 500);
        }
    }

    /**
     * 取得用戶信息
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request)
    {
        $token = $request->cookie('access_token');
        Log::info('取得用戶信息' . $token);
        JWTAuth::setToken($token);
        $user = JWTAuth::toUser($token);
        return response()->json([
            'status' => 'success',
            'user' => $user
        ], 200);
    }

    /**
     * 使令牌失效（登出）
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        // $token = $request->cookie('access_token');
        Log::info('登出');

        auth('api')->logout();
        // 清除所有相關的認證 cookie
        $accessTokenCookie = Cookie::forget('access_token');

        return response()->json([
            'status' => 'success',
            'message' => '成功登出'
        ])->withCookie($accessTokenCookie);
    }

    /**
     * 刷新令牌
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'access_token' => auth('api')->refresh(),
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
