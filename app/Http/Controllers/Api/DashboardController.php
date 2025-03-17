<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Collects;
use App\Models\File;
use App\Models\Work;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class DashboardController extends Controller
{
    public function work()
    {
        $user_id = JWTAuth::user()->id;
        $works = Work::orderBy('id', 'desc')->get(); // 使用 get() 而不是 all()
        return response()->json([
            'works' => $works,
            'user_id' => $user_id
        ]);
    }
    public function work_show(Request $request)
    {
        $request->validate([
            'id' => 'required|integer'
        ]);

        $id = $request->id;
        $userId = JWTAuth::user()->id;

        $work = Work::with([
            'files' => function ($query) {
                $query->orderBy('id', 'desc')->first();
            },
            'user' => function ($query) {
                $query->select('id', 'name');
            }
        ])->where('id', $id)->first();

        if ($work) {
            // 使用新的多态关系检查是否收藏
            $isCollected = Collects::where('user_id', $userId)
                ->where('collectable_id', $id)
                ->where('collectable_type', 'works')
                ->exists();

            // 将收藏状态添加到作品数据中
            $work->is_collected = $isCollected;
        }

        return response()->json([
            'work' => $work
        ]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
