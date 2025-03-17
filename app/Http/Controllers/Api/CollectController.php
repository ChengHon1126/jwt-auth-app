<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Collects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class CollectController extends Controller
{
    //
    public function toggleCollect(Request $request)
    {
        // 获取参数
        $collectableId = $request->input('collectable_id');
        $collectableType = $request->input('collectable_type');
        $user_id = JWTAuth::user()->id;

        // 使用鎖定機制避免競態條件
        DB::beginTransaction();
        try {
            // 适配旧参数 (work_id)
            if (!$collectableId && $request->has('id')) {
                $collectableId = $request->input('id');
                $collectableType = 'lesson'; // 或者使用 'works'，取决于您的实现
            }

            // 验证必要参数
            if (!$collectableId || !$collectableType) {
                return response()->json([
                    'status' => 'error',
                    'message' => '缺少必要参数'
                ], 400);
            }

            // 查询是否已收藏
            $collection = Collects::where('user_id', $user_id)
                ->where('collectable_id', $collectableId)
                ->where('collectable_type', $collectableType)
                ->lockForUpdate() // 锁定行防止并发问题
                ->first();

            if ($collection) {
                // 已收藏，取消收藏
                $collection->delete();
                $isCollected = false;
                $message = '取消收藏成功';
            } else {
                // 未收藏，添加收藏
                Collects::create([
                    'user_id' => $user_id,
                    'collectable_id' => $collectableId,
                    'collectable_type' => $collectableType,
                ]);
                $isCollected = true;
                $message = '收藏成功';
            }

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => $message,
                'is_collected' => $isCollected
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => '操作失敗: ' . $e->getMessage()
            ], 500);
        }
    }
}
