<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * 處理用戶註冊請求
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        // 驗證請求數據
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // 如果驗證失敗，返回錯誤信息
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // 創建新用戶
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 可選：創建 JWT token
        // $token = auth('api')->login($user);

        // 返回成功響應
        return response()->json([
            'status' => 'success',
            'message' => '用戶註冊成功',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at,
            ],
            // 如果要在註冊後立即登入，可以添加:
            // 'access_token' => $token,
            // 'token_type' => 'bearer',
            // 'expires_in' => auth('api')->factory()->getTTL() * 60
        ], 201);
    }
}
