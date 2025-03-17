<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Work;
use App\Models\WorkRating;
use Doctrine\DBAL\Schema\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class WorkRatingController extends Controller
{
    /**
     * 獲取作品的評論列表
     */
    public function getComments(Request $request)
    {
        $workId = $request->input('work_id');
        $comments = WorkRating::with(['user:id,name'])
            ->where('work_id', $workId)
            ->whereNotNull('comment')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($rating) {
                return [
                    'id' => $rating->id,
                    'user_id' => $rating->user_id,
                    'user_name' => $rating->user->name,
                    'rating' => $rating->rating,
                    'comment' => $rating->comment,
                    'created_at' => $rating->created_at,
                ];
            });
        $work = Work::find($workId);

        return response()->json([
            'dverageRating' => $work->getAverageRatingAttribute(),
            'ratingsCount' => $work->getRatingsCountAttribute(),
            'data' => $comments
        ]);
    }

    /**
     * 提交評分和評論
     */
    public function rateAndComment(Request $request)
    {
        // 驗證請求數據
        $validator = Validator::make($request->all(), [
            'work_id' => 'required|exists:works,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:200',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $userId = JWTAuth::user()->id;
        $workId = $request->input('work_id');
        $rating = $request->input('rating');
        $comment = $request->input('comment');

        // 檢查用戶是否已經評價過該作品
        $existingRating = WorkRating::where('user_id', $userId)
            ->where('work_id', $workId)
            ->first();

        if ($existingRating) {
            // 更新現有評價
            $existingRating->update([
                'rating' => $rating,
                'comment' => $comment,
            ]);
        } else {
            // 創建新評價
            WorkRating::create([
                'user_id' => $userId,
                'work_id' => $workId,
                'rating' => $rating,
                'comment' => $comment,
            ]);
        }

        // 重新計算作品的平均評分
        $work = Work::find($workId);
        $newRating = $work->getAverageRatingAttribute();
        $ratingsCount = $work->getRatingsCountAttribute();

        // 更新作品表中的評分字段（如果有的話）
        if (isset($work->rating)) {
            $work->rating = $newRating;
        }
        $work->save();

        return response()->json([
            'status' => 'success',
            'message' => '評價已提交',
            'new_rating' => $newRating,
            'ratings_count' => $ratingsCount
        ]);
    }
}
