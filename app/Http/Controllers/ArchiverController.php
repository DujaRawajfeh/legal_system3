<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ArchivedDocument;

class ArchiverController extends Controller
{
  public function index()
{
    // جلب المستخدم الحالي مع المحكمة والقلم المرتبطين به
    $archiver = auth()->user()->load(['tribunal','department']); 

    // جلب القضايا مع المحكمة والقلم المرتبطين بها
    $cases = \App\Models\CourtCase::with(['tribunal', 'department'])->get();

    // جلب الوثائق المؤرشفة
    $documents = ArchivedDocument::latest()->get();

    // استخراج السنة (مثلاً من أول قضية أو منطق آخر حسب المطلوب)
    $year = $cases->first()->year ?? date('Y');

    // إرسال البيانات للواجهة
    return view('clerk_dashboard.archiver', compact('cases', 'archiver', 'documents', 'year'));
}

public function store(Request $request)
{
    $request->validate([
        'court_case_id' => 'required|string', // المستخدم يدخل رقم الدعوى
        'document_type' => 'required|string|max:255',
        'document_file' => 'required|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:10240',
    ]);

    // ابحث عن القضية باستخدام رقم الدعوى (number)
    $case = \App\Models\CourtCase::where('number', $request->court_case_id)->first();

    if (!$case) {
        return back()->withErrors(['court_case_id' => 'رقم الدعوى غير موجود في قاعدة البيانات'])->withInput();
    }

    // توليد رقم الوثيقة
    $existingCount = ArchivedDocument::where('court_case_id', $case->id)->count();
    $documentNumber = $case->number . '/' . ($existingCount + 1);

    // تجهيز اسم الملف
    $file = $request->file('document_file');
    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
    $extension = $file->getClientOriginalExtension();
    $uniqueName = $originalName . '_' . time() . '.' . $extension;

    // انقل الملف فعليًا إلى مجلد public/uploads/archived_documents
    $file->move(public_path('uploads/archived_documents'), $uniqueName);

    // تخزين البيانات باستخدام الـ id الحقيقي للقضية
    ArchivedDocument::create([
        'court_case_id'   => $case->id, // نخزن الـ id
        'document_type'   => $request->document_type,
        'file_name'       => $uniqueName, // نخزن فقط الاسم
        'document_number' => $documentNumber,
    ]);

    return redirect()->route('archiver.page')->with('success', 'تمت أرشفة الوثيقة بنجاح');
}
}