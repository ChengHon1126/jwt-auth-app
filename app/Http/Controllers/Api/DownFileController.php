<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DownFileController extends Controller
{
    public function downloadFile(Request $request)
    {
        // 查找文件记录
        $id = $request->input('id');
        $file = File::findOrFail($id);
        // 文件的完整路径
        $filePath = public_path('storage/' . $file->file_path);
        // dd($filePath);

        // 检查文件是否存在
        if (!file_exists($filePath)) {
            return response()->json(['message' => '文件不存在'], 404);
        }

        // 下载文件
        return response()->download(
            $filePath,
            basename($file->file_path),
            [
                'Content-Type' => 'application/pdf',
            ]
        );
    }
}
