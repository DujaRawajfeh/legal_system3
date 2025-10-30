<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\CourtCase;
use App\Models\Participant;
use App\Models\User;
use App\Models\Notification;
use Carbon\Carbon;
use App\Models\IncomingProsecutorCase;
use App\Models\IncomingPoliceCase;
use App\Models\CaseTransfer;
use App\Models\CaseSession;


use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;
use App\Models\ArrestMemo;
use App\Models\Tribunal;
use App\Models\Department;










 


class WriterController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        return view('clerk_dashboard.writer', [
            'user' => $user,
            'courtName' => optional($user->tribunal)->name,
            'departmentName' => optional($user->department)->name,
            'userName' => $user->full_name,
        ]);
    }


  
public function storeCourtCase(Request $request)
{
    try {
        // توليد رقم عشوائي مكوّن من 4 أرقام غير مكرر
        do {
            $randomNumber = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
            $exists = CourtCase::where('number', $randomNumber)->exists();
        } while ($exists);

        // السنة الحالية (بالإنجليزي)
        $year = date('Y');

        // المستخدم الحالي
        $user = auth()->user();

        // جلب القضاة وتحديد القاضي التالي حسب الدور
        $judges = User::where('role', 'judge')->orderBy('id')->get();
        $lastCase = CourtCase::latest()->first();
        $lastJudgeId = $lastCase?->judge_id;
        $nextJudge = $judges->firstWhere('id', '>', $lastJudgeId) ?? $judges->first();

        // إنشاء القضية
        $case = CourtCase::create([
            'type' => $request->type,
            'number' => $randomNumber,
            'year' => $year,
            'judge_id' => $nextJudge->id,
            'tribunal_id' => $user->tribunal_id ?? null,
            'department_id' => $user->department_id ?? null,
            'created_by' => $user->id ?? null,
        ]);

        // تحديد موعد الجلسة: يوم عشوائي بين غدًا إلى بعد 6 أيام
        $hourOptions = [[8, 30], [15, 30]];
        do {
            $daysAhead = random_int(1, 6);
            $sessionDay = now()->addDays($daysAhead);
            $chosenTime = $hourOptions[array_rand($hourOptions)];
            $sessionDate = $sessionDay->setTime($chosenTime[0], $chosenTime[1]);

            $existingSession = \App\Models\CaseSession::where('judge_id', $nextJudge->id)
             ->where('session_date', $sessionDate)
             ->exists();
        } while ($existingSession);

        // تخزين الجلسة
        \App\Models\CaseSession::create([
            'court_case_id' => $case->id,
            'judge_id' => $nextJudge->id,
            'session_date' => $sessionDate,
            'created_by' => $user->id,
        ]);

        // إرجاع البيانات لـ JavaScript
        return response()->json([
            'id' => $case->id,
            'number' => $case->number,
            'year' => $case->year,
            'judge_id' => $nextJudge->id,
            'judge_name' => $nextJudge->full_name,
            'session_date' => $sessionDate->format('Y-m-d H:i'),
        ]);
    } catch (\Exception $e) {
        Log::error('❌ خطأ أثناء حفظ القضية:', ['message' => $e->getMessage()]);
        return response()->json([
            'error' => $e->getMessage()
        ], 500);
    }
}

public function show($id)
{
    $case = CourtCase::with('session')->find($id);
    return view('cases.show', compact('case'));
}




public function storeParticipant(Request $request)
{
    try {
        // تحقق من البيانات
        $validated = $request->validate([
            'court_case_id' => 'required|exists:court_cases,id',
            'type' => 'required|string',
            'name' => 'required|string',
            'national_id' => 'nullable|string',
            'residence' => 'nullable|string',
            'job' => 'nullable|string',
            'phone' => 'nullable|string',
        ]);

        // إنشاء الطرف
        $participant = Participant::create([
            'court_case_id' => $validated['court_case_id'],
            'type' => $validated['type'],
            'name' => $validated['name'],
            'national_id' => $validated['national_id'],
            'residence' => $validated['residence'],
            'job' => $validated['job'],
            'phone' => $validated['phone'],
        ]);

        return response()->json([

            'message' => 'تم حفظ الطرف بنجاح',
            'participant' => $participant,
        ]);
    } catch (\Exception $e) {
        Log::error('خطأ أثناء حفظ الطرف:', ['message' => $e->getMessage()]);
        return response()->json([
            'error' => $e->getMessage()
        ], 500);
    }
}



public function getNextAvailableJudge()
{
    $judges = User::where('role', 'judge')->orderBy('id')->get();
    $lastCase = CourtCase::latest()->first();
    $lastJudgeId = $lastCase?->judge_id;
    $nextJudge = $judges->firstWhere('id', '>', $lastJudgeId) ?? $judges->first();

    return response()->json([
        'judge_id' => $nextJudge->id,
        'full_name' => $nextJudge->full_name,
    ]);
}





/**
 * جلب تفاصيل قضية حسب رقمها، تشمل نوع الدعوى، رقم المحكمة، والأطراف المرتبطين.
 */
public function fetchCaseDetails($number)
{
    $case = CourtCase::where('number', $number)
    ->with(['tribunal', 'department', 'participants'])
    ->first();

    return response()->json($case);
}



 public function saveNotification(Request $request)
{
    \Log::info('📥 تم استقبال طلب تبليغ:', $request->all());

    $request->validate([
        'case_id' => 'required|string',
        'participant_name' => 'required|string|max:255',
        'method' => 'required|string|in:sms,email,قسم التباليغ',
    ]);

    // ✅ تحويل رقم القضية إلى ID
    $case = CourtCase::where('number', $request->case_id)->first();

    if (!$case) {
        return response()->json(['error' => 'رقم القضية غير موجود'], 422);
    }

    Notification::create([
        'case_id' => $case->id,
        'participant_name' => $request->participant_name,
        'method' => $request->method,
        'notified_at' => now()
    ]);

    return response()->json(['status' => 'success']);
}






























public function pullFromModal(Request $request)
{
    try {
        $caseNumber = $request->input('case_number');
        $courtLocation = $request->input('court_location');
        $prosecutorOffice = $request->input('prosecutor_office');

        // ✅ ترجمة السجل العام إلى القيمة الفعلية في الجدول
        $map = [
            'south' => 'السجل العام/جنوب عمان',
            'east'  => 'السجل العام/شرق عمان',
            'north' => 'السجل العام/شمال عمان',
        ];
        $translatedOffice = $map[$prosecutorOffice] ?? $prosecutorOffice;

        // ✅ تتبع قبل البحث
        Log::info('محاولة سحب دعوى', [
            'case_number' => $caseNumber,
            'records' => $translatedOffice,
        ]);

        // ✅ البحث عن الدعوى حسب رقمها والسجل العام
        $incoming = IncomingProsecutorCase::where('case_number', $caseNumber)
                    ->where('records', $translatedOffice)
                    ->first();

        if (!$incoming) {
            throw new \Exception("لا توجد دعوى بهذا الرقم والسجل العام المحدد");
        }

        // ✅ اختيار القاضي حسب القلم
        $judge = User::where('department_id', $incoming->department_id)
                     ->inRandomOrder()
                     ->first();

        if (!$judge) {
            throw new \Exception("لا يوجد قاضي مرتبط بالقلم رقم: {$incoming->department_id}");
        }

        // ✅ توليد رقم القضية الجديد
        $year = now()->year;
        $lastNumber = CourtCase::whereYear('created_at', $year)->max('number');
        $number = $lastNumber ? $lastNumber + 1 : 1;

        // ✅ إنشاء القضية كـ جنائية
        $courtCase = CourtCase::create([
            'judge_id'      => $judge->id,
            'type'          => 'جنائية',
            'number'        => $number,
            'year'          => $year,
            'tribunal_id'   => $incoming->tribunal_id,
            'department_id' => $incoming->department_id,
            'created_by'    => auth()->id(),
        ]);

        // ✅ إضافة الأطراف حسب النوع الواقعي
        Participant::create([
            'court_case_id' => $courtCase->id,
            'type'          => $incoming->plaintiff_type ?? 'مدعي',
            'name'          => trim($incoming->plaintiff_name),
            'national_id'   => $incoming->plaintiff_national_id,
            'residence'     => $incoming->plaintiff_residence,
            'job'           => $incoming->plaintiff_job,
            'phone'         => $incoming->plaintiff_phone,
        ]);

        Participant::create([
            'court_case_id' => $courtCase->id,
            'type'          => $incoming->defendant_type ?? 'مدعى عليه',
            'name'          => trim($incoming->defendant_name),
            'national_id'   => $incoming->defendant_national_id,
            'residence'     => $incoming->defendant_residence,
            'job'           => $incoming->defendant_job,
            'phone'         => $incoming->defendant_phone,
        ]);

        if (!empty($incoming->third_party_name)) {
            Participant::create([
                'court_case_id' => $courtCase->id,
                'type'          => $incoming->third_party_type ?? 'طرف ثالث',
                'name'          => trim($incoming->third_party_name),
                'national_id'   => $incoming->third_party_national_id,
                'residence'     => $incoming->third_party_residence,
                'job'           => $incoming->third_party_job,
                'phone'         => $incoming->third_party_phone,
            ]);
        }

        // ✅ إنشاء أول جلسة بعد 7 أيام
        \App\Models\CaseSession::create([
            'court_case_id' => $courtCase->id,
            'judge_id'      => $judge->id,
            'session_date'  => now()->addDays(7)->format('Y-m-d'),
        ]);

        // ✅ حذف الدعوى الأصلية
        $incoming->delete();

        return response()->json(['message' => 'تم سحب الدعوى وإنشاء الجلسة بنجاح']);
    } catch (\Exception $e) {
        Log::error('خطأ أثناء تنفيذ pullFromModal', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'case_number' => $request->input('case_number'),
            'prosecutor_office' => $request->input('prosecutor_office'),
        ]);

        return response()->json(['error' => $e->getMessage()], 500);
    }
}








//الشرطه
  // ✅ تعيين القاضي حسب القلم
// ✅ تعيين القاضي حسب القلم
public function assignJudge($departmentId)
{
    $judge = User::where('department_id', $departmentId)
        ->where('role', 'judge')
        ->first();

    Log::info('🎯 تعيين القاضي', [
        'department_id' => $departmentId,
        'judge_id'      => $judge?->id,
        'judgename'     => $judge?->full_name,
    ]);

    return $judge ? $judge->id : null;
}

// ✅ توليد رقم قضية من 4 أرقام عشوائية فقط
public function pullFromPoliceCase($id)
{
    Log::info('✅ تم استدعاء الدالة pullFromPoliceCase', ['incoming_id' => $id]);

    try {
        $user = auth()->user();
        Log::debug('👤 المستخدم الحالي', ['user' => $user]);

        if ($user->role !== 'writer') {
            Log::warning('⛔ المستخدم ليس كاتب');
            return response()->json(['message' => '⚠️ فقط المستخدمين من نوع كاتب يمكنهم سحب القضايا'], 403);
        }

        if (!$user->department_id || !$user->tribunal_id) {
            Log::warning('⚠️ القلم أو المحكمة غير معرفين للمستخدم');
            return response()->json(['message' => '⚠️ لا يمكن تحديد القلم أو المحكمة للمستخدم الحالي'], 422);
        }

        $incoming = IncomingPoliceCase::findOrFail($id);
        Log::debug('📄 القضية الشرطية المسحوبة', ['incoming' => $incoming]);

        $departmentId = $user->department_id;
        $tribunalId   = $user->tribunal_id;

        $judgeId = $this->assignJudge($departmentId);
        Log::debug('⚖️ القاضي المعين', ['judge_id' => $judgeId]);

        if (!$judgeId) {
            Log::warning('⚠️ لا يوجد قاضي مرتبط بهذا القلم');
            return response()->json(['message' => '⚠️ لا يوجد قاضي مرتبط بهذا القلم'], 422);
        }

        // ✅ توليد رقم قضية
        $caseNumber = rand(1000, 9999);
        Log::debug('🔢 رقم القضية القضائية', ['case_number' => $caseNumber]);

        // ✅ إنشاء القضية القضائية
        $courtCase = CourtCase::create([
            'type'          => $incoming->case_type,
            'number'        => $caseNumber,
            'year'          => now()->year,
            'tribunal_id'   => $tribunalId,
            'department_id' => $departmentId,
            'judge_id'      => $judgeId,
            'created_by'    => $user->id,
        ]);

        Log::info('✅ تم إنشاء القضية القضائية', ['court_case_id' => $courtCase->id]);

        // ✅ تسجيل الأطراف
        foreach (['plaintiff', 'defendant', 'third_party'] as $role) {
            $nameField = $role . '_name';
            if ($incoming->$nameField) {
                Participant::create([
                    'court_case_id' => $courtCase->id,
                    'type'          => $incoming->{$role . '_type'},
                    'name'          => $incoming->$nameField,
                    'national_id'   => $incoming->{$role . '_national_id'},
                    'residence'     => $incoming->{$role . '_residence'},
                    'job'           => $incoming->{$role . '_job'},
                    'phone'         => $incoming->{$role . '_phone'},
                ]);
                Log::debug("👥 تم تسجيل طرف: $role");
            }
        }

        // ✅ إنشاء جلسة تلقائية
        \App\Models\CaseSession::create([
            'court_case_id' => $courtCase->id,
            'judge_id'      => $judgeId,
            'session_date'  => now()->addDays(3),
            'status'        => 'مجدولة',
        ]);

        Log::info('✅ تم إنشاء الجلسة', ['court_case_id' => $courtCase->id]);

        // ✅ تسجيل التحويل
        $transfer = \App\Models\CaseTransfer::create([
            'source'         => 'شرطة',
            'source_case_id' => $incoming->id,
            'target_case_id' => $courtCase->id,
            'transferred_by' => $user->id,
            'transferred_at' => now(),
        ]);

        if (!$transfer || !$transfer->id) {
            Log::error('❌ فشل تسجيل التحويل فعليًا', [
                'source_case_id' => $incoming->id,
                'target_case_id' => $courtCase->id,
                'user_id'        => $user->id,
            ]);
            return response()->json(['message' => '❌ فشل تسجيل التحويل'], 500);
        }

        Log::info('✅ تم تسجيل التحويل فعليًا', [
            'transfer_id'     => $transfer->id,
            'source_case_id'  => $incoming->id,
            'target_case_id'  => $courtCase->id,
            'user_id'         => $user->id,
        ]);

        // ✅ حذف القضية من جدول الشرطة
        $incoming->delete();
        Log::info('🗑️ تم حذف القضية من جدول الشرطة', ['incoming_id' => $id]);

        return response()->json(['message' => '✅ تم سحب القضية وتحويلها بنجاح']);

    } catch (\Exception $e) {
        Log::error('❌ خطأ أثناء تنفيذ سحب القضية', [
            'incoming_id' => $id,
            'error'       => $e->getMessage(),
            'trace'       => $e->getTraceAsString(),
        ]);

        return response()->json(['message' => '❌ حدث خطأ أثناء سحب القضية'], 500);
    }
}
// ✅ عرض القضايا من جدول الشرطة حسب المركز
public function getPoliceCasesByCenter($center)
{
    // 🔍 تنظيف الاسم وإزالة المسافات الزائدة
    $center = trim($center);

    Log::info('📥 تم استدعاء getPoliceCasesByCenter', [
        'center_input'   => $center,
        'center_trimmed' => $center,
    ]);

    // 🔎 مطابقة جزئية لتجاوز الفروقات البسيطة
    $cases = IncomingPoliceCase::where('center_name', 'like', '%' . $center . '%')->get();

    if ($cases->isEmpty()) {
        Log::warning('⚠️ لا يوجد قضايا لهذا المركز', ['center_name' => $center]);
        return response()->json(['message' => '⚠️ لا يوجد قضايا لهذا المركز'], 404);
    }

    Log::info('✅ تم العثور على قضايا لهذا المركز', [
        'center_name' => $center,
        'count'       => $cases->count(),
    ]);

    return response()->json($cases);
}
















public function handleArrestMemo(Request $request) 
{
    // ✅ التحقق من إدخال رقم القضية فقط
    $request->validate([
        'case_number' => 'required',
        'detention_duration' => 'nullable|integer|min:1',
        'detention_reason' => 'nullable|string',
        'detention_center' => 'nullable|string',
        'judge_name' => 'nullable|string',
        'save' => 'nullable|boolean',
    ]);

    // ✅ جلب القضية مع العلاقات
    $case = CourtCase::with(['tribunal', 'department', 'judge'])
                     ->where('number', $request->case_number)
                     ->first();

    if (!$case) {
        return response()->json(['error' => 'القضية غير موجودة'], 404);
    }

    // ✅ جلب الأطراف
    $participants = Participant::where('court_case_id', $case->id)->get();

    // ✅ التحقق من أن القاضي فعلاً مستخدم نوعه "قاضي"
    $judge = optional($case->judge);
    $judgeName = ($judge && $judge->role === 'judge') ? trim($judge->full_name) : null;

    // ✅ إذا المستخدم طلب حفظ مذكرة التوقيف
    if ($request->has('save') && $request->save == true) {
        try {
            // التحقق من الحقول المطلوبة للحفظ
            $request->validate([
                'detention_duration' => 'required|integer|min:1',
                'detention_reason' => 'required|string',
                'detention_center' => 'required|string',
                'judge_name' => 'required|string',
            ]);

            // ✅ حفظ مذكرة التوقيف
            ArrestMemo::create([
                'case_id' => $case->id,
                'judge_name' => $request->judge_name,
                'detention_duration' => $request->detention_duration,
                'detention_reason' => $request->detention_reason,
                'detention_center' => $request->detention_center,
                'created_by' => auth()->id(),
            ]);

            return response()->json(['message' => 'تم حفظ مذكرة التوقيف بنجاح']);
        } catch (\Exception $e) {
            // ✅ تسجيل الخطأ في الـ log
            Log::error('خطأ أثناء حفظ مذكرة التوقيف', [
                'case_number' => $request->case_number,
                'judge_name' => $request->judge_name,
                'error' => $e->getMessage(),
            ]);

            return response()->json(['error' => 'حدث خطأ أثناء حفظ مذكرة التوقيف'], 500);
        }
    }

    // ✅ عرض البيانات بدون حفظ
    return response()->json([
        'case' => $case,
        'participants' => $participants,
        'judge_name' => $judgeName,
        'tribunal_number' => optional($case->tribunal)->number,
        'department_number' => optional($case->department)->number,
    ]);
}



// ✅ عرض تمديد مدة التوقيف
public function saveExtendArrestMemo(Request $request)
{
    try {
        // ✅ فك رقم الدعوى إلى رقم وسنة
        $rawNumber = $request->input('case_number'); // مثال: 123/2023
        [$number, $year] = explode('/', $rawNumber);

        // ✅ البحث عن القضية حسب الرقم والسنة
        $case = CourtCase::where('number', trim($number))
                         ->where('year', trim($year))
                         ->first();

        if (!$case) {
            return response()->json(['message' => 'القضية غير موجودة.'], 404);
        }

        // ✅ جلب الأطراف المرتبطين بالقضية
        $participants = Participant::where('court_case_id', $case->id)->get();

        if ($participants->isEmpty()) {
            return response()->json(['message' => 'لا يوجد أطراف مرتبطة بهذه القضية.'], 422);
        }

        // ✅ تحقق من باقي البيانات
        $validated = $request->validate([
            'judge_name' => 'required|string|max:255',
            'detention_duration' => 'required|integer|min:1',
            'detention_reason' => 'required|string|max:255',
            'detention_center' => 'required|string|max:255',
        ]);

        // ✅ إنشاء المذكرة
        $memo = new ArrestMemo();
        $memo->case_id = $case->id;
        $memo->judge_name = $validated['judge_name'];
        $memo->detention_duration = $validated['detention_duration'];
        $memo->detention_reason = $validated['detention_reason'];
        $memo->detention_center = $validated['detention_center'];
        $memo->memo_type = 'تمديد توقيف';
        $memo->created_by = auth()->id();
        $memo->created_at = now();
        $memo->save();

        // ✅ ربط الأطراف بالمذكرة
        foreach ($participants as $p) {
            ArrestMemoParticipant::create([
                'arrest_memo_id' => $memo->id,
                'participant_id' => $p->id,
            ]);
        }

        return response()->json(['message' => 'تم حفظ مذكرة التمديد بنجاح.']);
    } catch (\Throwable $e) {
        Log::error('خطأ أثناء حفظ مذكرة تمديد التوقيف: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
            'request' => $request->all()
        ]);

        return response()->json(['message' => 'حدث خطأ أثناء الحفظ.'], 500);
    }
}




































public function showFilteredSessions(Request $request)
{
    $type = $request->type;

    // فلترة الجلسات حسب النوع
    $query = DB::table('case_sessions')
        ->whereNotNull('court_case_id')
        ->orderBy('session_date', 'asc');

    switch ($type) {
        case 'today':
            $query->where('status', 'محددة')
                  ->whereDate('session_date', today());
            break;
        case 'upcoming':
            $query->where('status', 'محددة')
                  ->whereDate('session_date', '>', today());
            break;
        case 'finished':
            $query->where('status', 'منتهية');
            break;
        case 'postponed':
            $query->where('status', 'مؤجلة');
            break;
        case 'no_decision':
            $query->whereNull('judgment_type');
            break;
        case 'needs_action':
            $query->where('action_done', false);
            break;
        default:
            $query->whereRaw('1 = 0'); // لا تعرض شيء إذا النوع غير معروف
    }

    // تحميل الجلسات
    $sessionsRaw = $query->get();

    // تجهيز الجلسات مع اسم القاضي ورقم الدعوى
    $sessions = $sessionsRaw->map(function ($session) {
        $courtCase = DB::table('court_cases')->where('id', $session->court_case_id)->first();

        $judgeName = $courtCase
            ? DB::table('users')->where('id', $courtCase->judge_id)->value('full_name')
            : '-';

        $caseNumber = $courtCase->number ?? '-';

        $session->judge_name = $judgeName;
        $session->case_number = $caseNumber;

        return $session;
    });

    // معلومات المستخدم الحالي
    $user = auth()->user();

    $courtNumber = DB::table('tribunal')
        ->where('id', $user->tribunal_id)
        ->value('number') ?? '-';

    $departmentNumber = DB::table('department')
        ->where('id', $user->department_id)
        ->value('number') ?? '-';

    // عنوان النافذة حسب النوع
    $titles = [
        'today' => 'جلسات اليوم',
        'upcoming' => 'الجلسات القادمة',
        'finished' => 'الجلسات المنتهية',
        'postponed' => 'الجلسات المؤجلة',
        'no_decision' => 'جلسات بدون قرار',
        'needs_action' => 'جلسات تحتاج إجراء',
    ];
    $title = $titles[$type] ?? 'الجلسات';

    // ✅ تعريف $results لتجنب الخطأ في جدول المشاركين
    $results = collect();

    // ✅ تمرير كل المتغيرات المطلوبة للعرض
    return view('clerk_dashboard.writer', compact(
        'sessions',
        'title',
        'courtNumber',
        'departmentNumber',
        'results'
    ));
}


public function searchCivilRegistry(Request $request)
{
    $query = \App\Models\CivilRegistry::query();

    // فلترة حسب الحقول المدخلة
    if ($request->filled('first_name')) {
        $query->where('first_name', 'like', '%' . $request->first_name . '%');
    }
    if ($request->filled('father_name')) {
        $query->where('father_name', 'like', '%' . $request->father_name . '%');
    }
    if ($request->filled('mother_name')) {
        $query->where('mother_name', 'like', '%' . $request->mother_name . '%');
    }
    if ($request->filled('grandfather_name')) {
        $query->where('grandfather_name', 'like', '%' . $request->grandfather_name . '%');
    }
    if ($request->filled('family_name')) {
        $query->where('family_name', 'like', '%' . $request->family_name . '%');
    }
    if ($request->filled('occupation')) {
        $query->where('occupation', 'like', '%' . $request->occupation . '%');
    }
    if ($request->filled('nationality')) {
        $query->where('nationality', 'like', '%' . $request->nationality . '%');
    }
    if ($request->filled('birth_date')) {
        $query->whereDate('birth_date', $request->birth_date);
    }

    $results = $query->get();

    // إعادة نفس الصفحة مع نتائج البحث
    return view('clerk_dashboard.writer', [
        'results' => $results,
        'sessions' => [], // إذا فيه جلسات، ضيفيها حسب السياق
        'title' => 'المشاركون',
    ]);
}














    // ✅ دالة عرض بيانات الدعوى والمشاركين
    public function fetchReleaseMemoData(Request $request)
    {
        $caseId = $request->input('case_id');

        $courtCase = CourtCase::with(['judge' => function ($q) {
            $q->where('role', 'judge');
        }])->find($caseId);

        if (!$courtCase) {
            return response()->json(['error' => 'رقم الدعوى غير موجود'], 404);
        }

        $participants = Participant::where('court_case_id', $caseId)->get();
        $tribunal = Tribunal::first();
        $department = Department::first();

        return response()->json([
            'courtCase' => $courtCase,
            'participants' => $participants,
            'tribunal' => $tribunal,
            'department' => $department,
        ]);
    }

    // ✅ دالة حفظ مذكرة الإفراج
    public function storeReleaseMemo(Request $request)
    {
        $validated = $request->validate([
            'case_id' => 'required|exists:court_cases,id',
            'judge_name' => 'required|string',
            'detention_duration' => 'nullable|string',
            'detention_reason' => 'nullable|string',
            'detention_center' => 'nullable|string',
            'participant_name' => 'required|string',
        ]);

        $memo = ArrestMemo::create([
            'case_id' => $validated['case_id'],
            'judge_name' => $validated['judge_name'],
            'detention_duration' => $validated['detention_duration'],
            'detention_reason' => $validated['detention_reason'],
            'detention_center' => $validated['detention_center'],
            'created_by' => auth()->id(),
            'participant_name' => $validated['participant_name'],
            'released' => true,
        ]);

        return response()->json(['status' => 'success', 'memo_id' => $memo->id]);
    }
}
