<?php

namespace App\Http\Controllers;

use App\Models\Collects;
use App\Models\File;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use setasign\Fpdi\Tcpdf\Fpdi;
use Tymon\JWTAuth\Contracts\Providers\JWT;
use Tymon\JWTAuth\Facades\JWTAuth;

class WorkController extends Controller
{
    public function create()
    {
        return view('works.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'pdf' => 'required|mimes:pdf|max:10000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = $request->file('image') ? $request->file('image')->store('images', 'public') : null;

        // 原始 PDF 存儲
        $originalPdfName = $request->file('pdf')->getClientOriginalName();
        $originalPdfPath = $request->file('pdf')->store('original_pdfs', 'public');

        // 加密 PDF 文件
        $pdfFullPath = storage_path('app/public/' . $originalPdfPath);
        $encryptedPdfPath = basename($originalPdfPath);
        $encryptedPdfFullPath = storage_path('app/public/pdfs' . $encryptedPdfPath);

        $this->encryptPdf($pdfFullPath, $encryptedPdfFullPath, JWTAuth::user()->email);

        $work = Work::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => JWTAuth::user()->id,
            'image_path' => $imagePath,
        ]);

        File::create([
            'work_id' => $work->id,
            'file_path' => $encryptedPdfPath,
            'original_name' => $originalPdfName, // 新增原始文件名
        ]);

        return redirect()->route('dashboard')->with('success', '作品已成功上傳');
    }

    public function show($id)
    {
        return view('works.show', ['id' => $id]);
    }

    public function getAllPublishedWorks(Request $request)
    {
        $perPage = $request->input('per_page', 9);
        $sort = $request->input('sort', 'latest');
        $query = Work::with([
            'collects' => function ($query) {
                $query->where('user_id', JWTAuth::user()->id);
            }
        ])->where('progress', 'approved');
        if (isset($sort)) {
            if ($sort == 'latest') {
                $query->orderBy('id', 'desc')->get();
            } else if ($sort == 'mostCollected') {
                $query->withCount('collects')->orderBy('collects_count', 'desc')->get();
            }
        }
        // 製作分頁
        $works = $query->paginate($perPage);

        return response()->json(['works' => $works]);
    }

    public function getMyWorks(Request $request)
    {
        $user_id = JWTAuth::user()->id;
        $perPage = $request->input('per_page', 9);
        $page = $request->input('page', 1); // 接收頁碼參數， paginate() 方法會自動使用 page 參數

        // 直接在查詢構建器上使用 paginate，不要先呼叫 get()
        $works = Work::where('user_id', $user_id)
            ->orderBy('id', 'desc')
            ->paginate($perPage);

        return response()->json(['works' => $works]);
    }

    public function getPenndingWorks(Request $request)
    {
        $perPage = $request->input('per_page', 9);

        $works = Work::where('progress', 'pending')->orderBy('id', 'desc')->paginate($perPage);

        return response()->json(['works' => $works]);
    }

    public function getCollectsWork(Request $request)
    {
        $user_id = JWTAuth::user()->id;
        $perPage = $request->input('per_page', 9);
        $page = $request->input('page', 1);

        // 只查询作品类型的收藏
        $collects = Collects::where('user_id', $user_id)
            ->Where('collectable_type', 'works') // 兼容旧数据
            ->orderBy('id', 'desc')
            ->paginate($perPage);

        // 加载关联的作品
        $collects->getCollection()->transform(function ($collect) {
            // 手动加载作品信息
            $work = \App\Models\Work::select('id', 'title', 'description', 'image_path', 'user_id', 'created_at')
                ->find($collect->collectable_id);

            // 将作品信息设置到 work 属性，保持与原来相同的数据结构
            $collect->work = $work;

            return $collect;
        });

        return response()->json(['collects' => $collects]);
    }
    private function encryptPdf($inputPath, $outputPath, $password)
    {
        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile($inputPath);

        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $templateId = $pdf->importPage($pageNo);
            $size = $pdf->getTemplateSize($templateId);

            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($templateId);
        }

        $pdf->SetProtection(['print'], $password);
        $pdf->Output($outputPath, 'F');
    }



    public function submitForReview(Request $request)
    {
        $id = $request->input('work_id');
        if (!$id) {
            return response()->json(['message' => '未提供作品ID'], 400);
        }
        $work = Work::where('id', $id)->first();
        if ($work) {
            $work->progress = 'pending';
            $work->save();

            return response()->json([
                'status' => 'success',
                'message' => '作品已提交審核'
            ]);
        } else {
            return response()->json([
                'message' => '未找到對應作品'
            ], 404);
        }
    }

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
            if (!$collectableId && $request->has('work_id')) {
                $collectableId = $request->input('work_id');
                $collectableType = 'works'; // 或者使用 'works'，取决于您的实现
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
