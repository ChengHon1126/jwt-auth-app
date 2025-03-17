<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Work;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function reviewWorks()
    {
        return view('admin.review-works');
    }
    public function reviewShow($id)
    {
        return view('admin.review-works-show', ['id' => $id]);
    }

    public function lessonPlancreate()
    {
        return view('lesson-plans.create');
    }

    public function reviewLessons()
    {
        return view('lesson-plans.show');
    }

    function reviews(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $reviews = Work::with([
            'user' => function ($query) {
                $query->select('id', 'name');
            },
        ])->where('progress', 'pending')->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $reviews
        ]);
    }

    function approveWork(Request $request)
    {
        $id = $request->input('work_id');
        $work = Work::find($id);
        $work->progress = 'approved';
        $work->save();
        return response()->json([
            'status' => 'success',
            'message' => '作品已審核通過'
        ]);
    }
}
