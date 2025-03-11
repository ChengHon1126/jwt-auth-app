<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use setasign\Fpdi\Tcpdf\Fpdi;

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
        $pdfPath = $request->file('pdf')->store('pdfs', 'public');

        // 加密 PDF 文件
        $pdfFullPath = storage_path('app/public/' . $pdfPath);
        $encryptedPdfPath = 'pdfs/encrypted_' . basename($pdfPath);
        $encryptedPdfFullPath = storage_path('app/public/' . $encryptedPdfPath);

        $this->encryptPdf($pdfFullPath, $encryptedPdfFullPath, Auth::user()->email);

        // 刪除未加密的 PDF 文件
        unlink($pdfFullPath);

        $work = Work::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => Auth::id(),
            'image_path' => $imagePath,
        ]);


        File::create([
            'work_id' => $work->id,
            'file_path' => $pdfPath,
            'status' => 'active',
        ]);

        return redirect()->route('dashboard')->with('success', '作品已成功上傳');
    }

    public function show($id)
    {
        $work = Work::findOrFail($id);
        return view('works.show', compact('work'));
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
}
