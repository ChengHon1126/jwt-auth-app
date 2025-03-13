<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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

        // 嘗試登入
        $credentials = $request->only('email', 'password');

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'error' => '提供的憑證不正確'
            ], 401);
        }
        // Cookie::make($name, $value, $minutes, $path, $domain, $secure, $httpOnly)
        // $name：Cookie 的名稱。
        // $value：Cookie 的值。
        // $minutes：Cookie 的有效期（以分鐘為單位）。
        // $path：Cookie 的路徑。
        // $domain：Cookie 的域名。
        // $secure：是否僅限 HTTPS。
        // $httpOnly：是否僅限 HTTP（設置為 true 表示 HTTP Only）。
        // 登入成功，返回JWT令牌
        return response()->json([
            'status' => 'success',
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

    /**
     * 取得用戶信息
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth('api')->user());
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
