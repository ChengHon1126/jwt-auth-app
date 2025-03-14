<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;

class JwtAuthenticateFromCookie
{
    public function handle(Request $request, Closure $next)
    {
        // 先從 cookie 取得 token
        // Log::info($request->headers->get('Authorization'));
        $token = $request->cookie('access_token');
        if (!$token) {
            if ($request->expectsJson()) {
                return response()->json(['message' => '未授權'], 401);
            }
            return redirect()->route('login');
        }

        try {
            // 設置 token 到請求頭
            $request->headers->set('Authorization', 'Bearer ' . $token);

            // 嘗試驗證 token
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                throw new \Exception('找不到用戶');
            }
        } catch (TokenExpiredException $e) {
            Log::info('Token 已過期');
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Token 已過期'], 401);
            }
            return redirect()->route('login')->with('error', '您的登入已過期，請重新登入');
        } catch (TokenInvalidException $e) {
            Log::info('Token 無效');
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Token 無效'], 401);
            }
            return redirect()->route('login')->with('error', '登入憑證無效，請重新登入');
        } catch (\Exception $e) {
            Log::info('Token 驗證問題: ' . $e->getMessage());
            if ($request->expectsJson()) {
                return response()->json(['message' => '認證失敗'], 401);
            }
            return redirect()->route('login');
        }

        return $next($request);
    }
}
