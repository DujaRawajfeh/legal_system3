<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ArchivedDocument;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ArchiverController extends Controller
{
    public function index()
    {
        $archiver = auth()->user()->load(['tribunal','department']); 
        $cases = \App\Models\CourtCase::with(['tribunal', 'department'])->get();
        $documents = ArchivedDocument::latest()->get();
        $year = $cases->first()->year ?? date('Y');

        return view('clerk_dashboard.archiver', compact('cases', 'archiver', 'documents', 'year'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'court_case_id' => 'required|string',
            'document_type' => 'required|string|max:255',
            'document_file' => 'required|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:10240',
        ]);

        $case = \App\Models\CourtCase::where('number', $request->court_case_id)->first();

        if (!$case) {
            return back()->withErrors([
                'court_case_id' => 'رقم الدعوى غير موجود في قاعدة البيانات'
            ])->withInput();
        }

        $existingCount = ArchivedDocument::where('court_case_id', $case->id)->count();
        $documentNumber = $case->number . '/' . ($existingCount + 1);

        $file = $request->file('document_file');
        $destinationPath = public_path('uploads/archived_documents');
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }

        $extension = $file->getClientOriginalExtension();
        $uniqueName = time() . '_' . uniqid() . '.' . $extension;
        $path = $file->storeAs('archived_documents', $uniqueName, 'public');

        $archivedDocument = ArchivedDocument::create([
            'court_case_id'   => $case->id,
            'document_type'   => $request->document_type,
            'file_name'       => $uniqueName,
            'document_number' => $documentNumber,
        ]);

        // إرسال البيانات للبلوك تشين
        try {
            $blockchainController = app(\App\Http\Controllers\BlockchainController::class);
            $blockchainController->archive(new Request([
                'caseNumber'     => $case->number,
                'documentNumber' => $documentNumber,
                'documentType'   => $request->document_type,
            ]));
        } catch (\Exception $e) {
            Log::error("Blockchain error: " . $e->getMessage());
        }

        return redirect()
            ->route('archiver.page')
            ->with('success', 'تمت أرشفة الوثيقة بنجاح');
    }
}
