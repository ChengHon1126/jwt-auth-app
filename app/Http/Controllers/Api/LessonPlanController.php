<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LessonPlanGrade;
use App\Models\LessonPlans;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class LessonPlanController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $lessonPlans = LessonPlans::with([
            'grades' => function ($query) {
                $query->select('lesson_plan_id', 'grade_level');
            },
        ])->where('is_delete', false)->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $lessonPlans
        ]);
    }
    public function create(Request $request)
    {
        // 驗證請求數據
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'grade_levels' => 'required|array',
            'grade_levels.*' => 'in:elementary,junior_high,senior_high',
            'teaching_goals' => 'nullable|string',
            'activities' => 'nullable|string',
            'file' => 'required|file|mimes:pdf|max:10240', // 最大 10MB
        ], [

            'title.required' => '教案標題不能為空',
            'title.max' => '教案標題不能超過255個字符',
            'grade_levels.required' => '請選擇適用年級',
            'grade_levels.array' => '適用年級格式不正確',
            'grade_levels.*.in' => '適用年級選項不正確',
            'file.required' => '請上傳教案檔案',
            'file.file' => '上傳的必須是檔案',
            'file.mimes' => '請上傳PDF格式的檔案',
            'file.max' => '檔案大小不能超過10MB',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        DB::beginTransaction();
        try {
            // 存儲檔案
            $path = $request->file('file')->store('lesson_plans', 'public');

            // 創建教案記錄
            $lessonPlan = LessonPlans::create([
                'work_id' => $request->work_id,
                'user_id' => JWTAuth::user()->id,
                'title' => $request->title,
                'description' => $request->description,
                'file_path' => $path,
                'grade_level' => implode(',', $request->grade_levels), // 存儲為逗號分隔的字符串
                'teaching_goals' => $request->teaching_goals,
                'activities' => $request->activities,
            ]);

            // 如果使用關聯表存儲年級標籤（可選）
            foreach ($request->grade_levels as $gradeLevel) {
                LessonPlanGrade::create([
                    'lesson_plan_id' => $lessonPlan->id,
                    'grade_level' => $gradeLevel
                ]);
            }
            DB::commit();
            // 返回成功響應
            return response()->json([
                'message' => '教案上傳成功',
                'lesson_plan' => $lessonPlan,
            ], 201);
        } catch (\Exception $e) {
            // 記錄錯誤
            Log::error('教案上傳失敗: ' . $e->getMessage());
            DB::rollBack();
            // 返回錯誤響應
            return response()->json([
                'message' => '教案上傳失敗，請稍後再試',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    // 發布教案
    function push(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => [
                'required',
                'integer',
                'exists:lesson_plans,id', // 增加存在性驗證
            ],
        ], [
            'id.required' => 'ID不能為空',
            'id.integer' => 'ID必須是整數',
            'id.exists' => '指定的課程計劃不存在'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $lessonPlan = LessonPlans::findOrFail($request->id);

            // 增加檢查是否已經審核通過
            if ($lessonPlan->is_approved) {
                return response()->json([
                    'status' => 'warning',
                    'message' => '此教案已經通過審核'
                ], 400);
            }

            $lessonPlan->is_approved = true;
            $lessonPlan->approved_at = now(); // 建議記錄審核時間
            $lessonPlan->approved_user_id = JWTAuth::user()->id; // 建議記錄審核人
            $lessonPlan->save();

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => '教案已通過審核',
                'data' => $lessonPlan
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('教案審核失敗: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => '操作失敗',
                'error' => config('app.debug') ? $e->getMessage() : '系統錯誤'
            ], 500);
        }
    }

    function delete(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|exists:lesson_plans,id'
        ]);
        $lessonPlan = LessonPlans::findOrFail($validatedData['id']);
        $lessonPlan->is_delete = true;
        $lessonPlan->deleted_at = now();
        $lessonPlan->deleted_user_id = JWTAuth::user()->id;
        $lessonPlan->save();
        return response()->json([
            'status' => 'success',
            'message' => '教案已刪除'
        ], 204);
    }
}
