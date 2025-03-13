<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SetAccessTokenCookie
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // 嘗試從響應中獲取訪問令牌
        // 如果響應是 JSON 響應，可能需要從 JSON 內容中提取
        if ($response instanceof \Illuminate\Http\JsonResponse) {
            $content = json_decode($response->getContent(), true);
            $token = $content['access_token'] ?? null;
        } else {
            // 或者檢查在響應頭部或其他地方設置的令牌
            $token = $response->headers->get('X-Access-Token') ?? null;
        }

        if ($token) {
            Log::info('SetAccessTokenCookie middleware: 設置訪問令牌');

            // 創建一個安全的 cookie
            // Secure => true (只在 HTTPS 上傳輸)
            // HttpOnly => true (防止 JavaScript 訪問)
            // SameSite => 'lax' (只在同一站點或從其他站點的頂級導航時發送)
            $cookie = Cookie::make(
                'access_token',    // 名稱
                $token,            // 值
                60,                // 過期分鐘數
                '/',               // 路徑
                config('session.domain'), // 域名，使用配置中的值
                config('session.secure'), // 是否只在 HTTPS 上傳輸
                true,              // HTTP Only
                false,             // Raw
                'lax'              // SameSite 策略
            );

            // 清除response 的token
            if ($response instanceof \Illuminate\Http\JsonResponse) {
                $content['access_token'] = null;
                $response->setContent(json_encode($content));
            } else {
                $response->headers->remove('X-Access-Token');
            }

            // 將 cookie 添加到響應
            $response = $response->withCookie($cookie);
        }

        return $response;
    }
}
