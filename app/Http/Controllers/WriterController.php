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
use App\Models\RequestSchedule;
use App\Models\CourtSessionReport;



class WriterController extends Controller
{
   public function dashboard()
{
    $user = Auth::user();//بجيب معلومات المستخدم اللي سجل دخول

    // تجهيز متغير النتائج لتفادي الخطأ في الواجهة
    $results = [];

    //  جلب السجلّات من قاعدة البيانات لعرضها في نافذة "سحب دعوى"
    $records = IncomingProsecutorCase::select('records')
                ->distinct()
                ->whereNotNull('records')
                ->orderBy('records')
                ->get();

    return view('clerk_dashboard.writer', [
        'user' => $user,
        'courtName' => optional($user->tribunal)->name,
        'departmentName' => optional($user->department)->name,
        'userName' => $user->full_name,
        'results' => $results,
        'records' => $records, // ← مهم لعرض السجل العام داخل المودال
    ]);
}






  //تسجيل دعوى
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

    //  القيمة الافتراضية المطلوبة
    'status' => 'محددة',
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
        Log::error(' خطأ أثناء حفظ القضية:', ['message' => $e->getMessage()]);
        return response()->json([
            'error' => $e->getMessage()
        ], 500);
    }
}
//تسجيل دعوى
public function show($id)
{
    $case = CourtCase::with('session')->find($id);
    return view('cases.show', compact('case'));
}

//تسجيل دعوى
// تسجيل دعوى — حفظ الأطراف داخل participants
public function storeParticipant(Request $request)
{
    try {
        // تحقق من البيانات
        $validated = $request->validate([
            'court_case_id' => 'required|exists:court_cases,id',
            'type'          => 'required|string',
            'name'          => 'required|string',
            'national_id'   => 'nullable|string',
            'residence'     => 'nullable|string',
            'job'           => 'nullable|string',
            'phone'         => 'nullable|string',
            'charge'        => 'nullable|string',  
        ]);

        // إنشاء الطرف
        $participant = Participant::create([
            'court_case_id' => $validated['court_case_id'],
            'type'          => $validated['type'],
            'name'          => $validated['name'],
            'national_id'   => $validated['national_id'],
            'residence'     => $validated['residence'],
            'job'           => $validated['job'],
            'phone'         => $validated['phone'],
            'charge'        => $validated['charge'],  
        ]);

        return response()->json([
            'message'     => 'تم حفظ الطرف بنجاح',
            'participant' => $participant,
        ]);

    } catch (\Exception $e) {

        Log::error('خطأ أثناء حفظ الطرف:', [
            'message' => $e->getMessage()
        ]);

        return response()->json([
            'error' => $e->getMessage()
        ], 500);
    }
}
//تسجيل دعوى
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




 //مذكرة تبليغ مشتكي موعد جلسة
public function fetchCaseDetails($number, Request $request)
{
    \Log::info(' بدء جلب تفاصيل القضية من نافذة المذكرات', [
        'case_number'  => $number,
        'query_params' => $request->all(),
    ]);

    $case = CourtCase::where('number', $number)
        ->with([
            'tribunal',
            'department',
            'participants',
            'judge'
        ])
        ->first();

    if (!$case) {
        \Log::warning(' رقم القضية غير موجود عند جلب تفاصيل المذكرة', [
            'case_number' => $number,
        ]);

        return response()->json(['error' => 'رقم القضية غير موجود'], 422);
    }

    $case->load('caseJudgment');

    $notificationType = is_array($request->query('notification_type'))
        ? $request->query('notification_type')['type'] ?? null
        : $request->query('notification_type');

    try {
        $filteredParticipants = $this->filterParticipantsByNotificationType(
            $case->participants,
            $notificationType
        );

        $case->participants = $filteredParticipants;

    

    } catch (\Exception $e) {

        \Log::error(' خطأ أثناء فلترة الأطراف في fetchCaseDetails', [
            'case_number'       => $number,
            'notification_type' => $notificationType,
            'message'           => $e->getMessage(),
        ]);

        return response()->json(['error' => $e->getMessage()], 422);
    }

    \Log::info('✅ تم جلب تفاصيل القضية بنجاح من fetchCaseDetails', [
        'case_number'       => $number,
        'case_id'           => $case->id,
        'notification_type' => $notificationType,
        'participants_count'=> $case->participants->count(),
    ]);

    // ✅ التعديل الوحيد هنا
    return response()->json([
        'case_id'    => $case->id,
        'number'     => $case->number,
        'case_type'  => $case->type,
        'judge_name' => $case->judge->full_name ?? '-',
        'tribunal'    => $case->tribunal,
        'department'  => $case->department,
        'participants'=> $case->participants,
        'judgment' => $case->caseJudgment? $case->caseJudgment->judgment_summary: null,
    ]);
}
public function saveNotification(Request $request)
{
    \Log::info(' بدء استقبال طلب تبليغ');

    try {
        \Log::info(' البيانات المستلمة:', $request->all());

        $request->validate([
            'case_id' => 'required|integer',
            'participant_name' => 'required|string|max:255',
            'method' => 'required|string|in:sms,email,قسم التباليغ',
        ]);

        \Log::info(' التحقق من البيانات تم بنجاح');

        // تحويل رقم القضية إلى ID
        $case = CourtCase::find($request->case_id);

        if (!$case) {
            \Log::warning(" القضية غير موجودة: {$request->case_id}");
            return response()->json(['error' => 'رقم القضية غير موجود'], 422);
        }

        Notification::create([
            'case_id' => $case->id,
            'participant_name' => $request->participant_name,
            'method' => $request->method,
            'notified_at' => now()
        ]);

        \Log::info(" تم حفظ التبليغ للطرف: {$request->participant_name} بطريقة: {$request->method}");

        return response()->json(['status' => 'success']);
    } 
    catch (\Throwable $e) {

        //  Logging كامل للخطأ
        \Log::error(' خطأ أثناء حفظ التبليغ:', [
            'error_message' => $e->getMessage(),
            'case_id_received' => $request->case_id,
            'participant_name_received' => $request->participant_name,
            'method_received' => $request->method,
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'error' => 'حدث خطأ داخلي أثناء الحفظ'
        ], 500);
    }
}
/**
 * فلترة الأطراف حسب نوع المذكرة
 */
private function filterParticipantsByNotificationType($participants, $notificationType)
{
    // إزالة المسافات والاختلافات
    $notificationType = trim($notificationType);

    // تحديد الأنواع المقبولة لكل مذكرة
    if (str_contains($notificationType, 'مشتكى عليه')) {
        $requiredTypes = ['مشتكى عليه'];
    }
    elseif (str_contains($notificationType, 'مشتكي موعد جلسة')) {
        $requiredTypes = ['مشتكي'];
    }
    elseif (str_contains($notificationType, 'شاهد موعد جلسة') || str_contains($notificationType, 'خاصة بالشهود')) {
        $requiredTypes = ['شاهد'];
    }
    else {
        return $participants; // غير داخلة بالتبليغات
    }

    // فلترة الأطراف حسب النوع
    $filtered = collect($participants)->filter(function ($p) use ($requiredTypes) {
        return in_array(trim($p->type), $requiredTypes);
    });

    // لو ما في ولا طرف → ارمي رسالة
    if ($filtered->isEmpty()) {
        $typeName = implode(' أو ', $requiredTypes);
        throw new \Exception("لا يوجد طرف من نوع {$typeName} في هذه الدعوى.");
    }

    return $filtered->values();
}







//سحب دعوى من المدعي العام
public function pullFromModal(Request $request)
{
    try {
        $caseNumber = $request->input('case_number');
        $courtLocation = $request->input('court_location');
        $prosecutorOffice = $request->input('prosecutor_office'); //  تأتي من الواجهة بنفس نص DB

        //  تتبع قبل البحث
        Log::info('محاولة سحب دعوى', [
            'case_number' => $caseNumber,
            'records' => $prosecutorOffice,
        ]);

        //  البحث حسب رقم الدعوى وقيمة records كما هي في قاعدة البيانات
        $incoming = IncomingProsecutorCase::where('case_number', $caseNumber)
                    ->where('records', $prosecutorOffice)
                    ->first();

        if (!$incoming) {
            throw new \Exception("لا توجد دعوى بهذا الرقم والسجل العام المحدد");
        }

        // اختيار قاضي مرتبط بالقلم
        $judge = User::where('department_id', $incoming->department_id)
                     ->inRandomOrder()
                     ->first();

        if (!$judge) {
            throw new \Exception("لا يوجد قاضي مرتبط بالقلم رقم: {$incoming->department_id}");
        }

        // توليد رقم القضية الجديد
        $year = now()->year;
        $lastNumber = CourtCase::whereYear('created_at', $year)->max('number');
        $number = $lastNumber ? $lastNumber + 1 : 1;

        // إنشاء القضية
        $courtCase = CourtCase::create([
    'judge_id'      => $judge->id,
    'type'          => $incoming->title, // ← أخذ النوع من عنوان الدعوى
    'number'        => $number,
    'year'          => $year,
    'tribunal_id'   => $incoming->tribunal_id,
    'department_id' => $incoming->department_id,
    'created_by'    => auth()->id(),
]);
        // الأطراف الأساسية
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

        // طرف ثالث (إن وجد)
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

        // إنشاء أول جلسة بعد 7 أيام
        \App\Models\CaseSession::create([
            'court_case_id' => $courtCase->id,
            'judge_id'      => $judge->id,
            'session_date'  => now()->addDays(7)->format('Y-m-d'),
        ]);

        // حذف الدعوى الأصلية
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
//  سحب دعوى من الشرطة
public function assignJudge($departmentId)
{
    $judge = User::where('department_id', $departmentId)
        ->where('role', 'judge')
        ->first();

    Log::info(' تعيين القاضي', [
        'department_id' => $departmentId,
        'judge_id'      => $judge?->id,
        'judgename'     => $judge?->full_name,
    ]);

    return $judge ? $judge->id : null;
}
//  توليد رقم قضية من 4 أرقام عشوائية فقط
public function pullFromPoliceCase($id)
{
    Log::info(' تم استدعاء الدالة pullFromPoliceCase', ['incoming_id' => $id]);

    try {
        $user = auth()->user();
        Log::debug(' المستخدم الحالي', ['user' => $user]);

        if ($user->role !== 'writer') {
            Log::warning(' المستخدم ليس كاتب');
            return response()->json(['message' => 'فقط المستخدمين من نوع كاتب يمكنهم سحب القضايا'], 403);
        }

        if (!$user->department_id || !$user->tribunal_id) {
            Log::warning(' القلم أو المحكمة غير معرفين للمستخدم');
            return response()->json(['message' => ' لا يمكن تحديد القلم أو المحكمة للمستخدم الحالي'], 422);
        }

        $incoming = IncomingPoliceCase::findOrFail($id);
        Log::debug(' القضية الشرطية المسحوبة', ['incoming' => $incoming]);

        $departmentId = $user->department_id;
        $tribunalId   = $user->tribunal_id;

        $judgeId = $this->assignJudge($departmentId);
        Log::debug(' القاضي المعين', ['judge_id' => $judgeId]);

        if (!$judgeId) {
            Log::warning(' لا يوجد قاضي مرتبط بهذا القلم');
            return response()->json(['message' => 'لا يوجد قاضي مرتبط بهذا القلم'], 422);
        }

        //  توليد رقم قضية
        $caseNumber = rand(1000, 9999);
        Log::debug(' رقم القضية القضائية', ['case_number' => $caseNumber]);

        //  إنشاء القضية القضائية
        $courtCase = CourtCase::create([
            'type'          => $incoming->case_type,
            'number'        => $caseNumber,
            'year'          => now()->year,
            'tribunal_id'   => $tribunalId,
            'department_id' => $departmentId,
            'judge_id'      => $judgeId,
            'title'         => $incoming->title,
            'records'       => $incoming->records,
            'created_by'    => $user->id,
        ]);

        Log::info(' تم إنشاء القضية القضائية', ['court_case_id' => $courtCase->id]);

        //  تسجيل الأطراف
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
                Log::debug(" تم تسجيل طرف: $role");
            }
        }

        // إنشاء جلسة تلقائية
        CaseSession::create([
            'court_case_id' => $courtCase->id,
            'judge_id'      => $judgeId,
            'session_date'  => now()->addDays(3),
            'status'        => 'محددة',
        ]);

        Log::info(' تم إنشاء الجلسة', ['court_case_id' => $courtCase->id]);

        //  حذف القضية من جدول الشرطة
        $incoming->delete();
        Log::info(' تم حذف القضية من جدول الشرطة', ['incoming_id' => $id]);

        return response()->json(['message' => ' تم سحب القضية وتحويلها بنجاح']);

    } catch (\Exception $e) {
        Log::error(' خطأ أثناء تنفيذ سحب القضية', [
            'incoming_id' => $id,
            'error'       => $e->getMessage(),
            'trace'       => $e->getTraceAsString(),
        ]);

        return response()->json(['message' => ' حدث خطأ أثناء سحب القضية'], 500);
    }
}
//  عرض القضايا من جدول الشرطة حسب المركز
public function getPoliceCasesByCenter($center)
{
    // تنظيف الاسم وإزالة المسافات الزائدة
    $center = trim($center);

    Log::info(' تم استدعاء getPoliceCasesByCenter', [
        'center_input'   => $center,
        'center_trimmed' => $center,

    ]);

    //  مطابقة جزئية لتجاوز الفروقات البسيطة
    $cases = IncomingPoliceCase::where('center_name', 'like', '%' . $center . '%')->get();

    if ($cases->isEmpty()) {
        Log::warning(' لا يوجد قضايا لهذا المركز', ['center_name' => $center]);
        return response()->json(['message' => ' لا يوجد قضايا لهذا المركز'], 404);
    }

    Log::info(' تم العثور على قضايا لهذا المركز', [
        'center_name' => $center,
        'count'       => $cases->count(),
    ]);

    return response()->json($cases);
}










// مذكرة توقيف
public function handleArrestMemo(Request $request) 
{
    //  التحقق من إدخال رقم القضية فقط
    $request->validate([
        'case_number' => 'required',
        'detention_duration' => 'nullable|integer|min:1',
        'detention_reason' => 'nullable|string',
        'detention_center' => 'nullable|string',
        'participant_name' => 'nullable|string', 
        'save' => 'nullable|boolean',
    ]);

    //  جلب القضية مع العلاقات
    $case = CourtCase::with(['tribunal', 'department', 'judge'])
                     ->where('number', $request->case_number)
                     ->first();

    if (!$case) {
        return response()->json(['error' => 'القضية غير موجودة'], 404);
    }

    //  جلب الأطراف
    $participants = Participant::where('court_case_id', $case->id)->get();

    //  جلب اسم القاضي من العلاقة
    $judgeName = optional($case->judge)->full_name;

    //  إذا المستخدم طلب حفظ مذكرة التوقيف
    if ($request->has('save') && $request->save == true) {
        try {
            //  التحقق من الحقول المطلوبة للحفظ
            $request->validate([
                'detention_duration' => 'required|integer|min:1',
                'detention_reason' => 'required|string',
                'detention_center' => 'required|string',
                'participant_name' => 'required|string', 
            ]);

            //  حفظ مذكرة التوقيف
            ArrestMemo::create([
                'case_id'            => $case->id,
                'judge_name'         => $judgeName,
                'participant_name'   => $request->participant_name, 
                'detention_duration' => $request->detention_duration,
                'detention_reason'   => $request->detention_reason,
                'detention_center'   => $request->detention_center,
                'created_by'         => auth()->id(),
            ]);

            return response()->json(['message' => 'تم حفظ مذكرة التوقيف بنجاح']);
        } catch (\Exception $e) {
            //  تسجيل الخطأ في الـ log
            Log::error('خطأ أثناء حفظ مذكرة التوقيف', [
                'case_number' => $request->case_number,
                'judge_name'  => $judgeName,
                'participant_name' => $request->participant_name,
                'error'       => $e->getMessage(),
            ]);

            return response()->json(['error' => 'حدث خطأ أثناء حفظ مذكرة التوقيف'], 500);
        }
    }

    //  عرض البيانات بدون حفظ
    return response()->json([
        'case'              => $case,
        'participants'      => $participants,
        'judge_name'        => $judgeName,
        'tribunal_number'   => optional($case->tribunal)->number,
        'department_number' => optional($case->department)->number,
    ]);
}








//مذكرة تمديد توقيف
public function extendArrestMemo(Request $request) 
{
    Log::info(' دخول الدالة extendArrestMemo', [
        'request' => $request->all()
    ]);

    //  تحقق مرن حسب نوع الطلب
    if ($request->has('save') && $request->save == true) {
        $request->validate([
            'case_number' => 'required',
            'extension_days' => 'required|integer|min:1',
            'detention_reason' => 'required|string',
            'detention_center' => 'required|string',
            'participant_name' => 'required|string',
            'save' => 'nullable|boolean',
        ]);
    } else {
        $request->validate([
            'case_number' => 'required',
        ]);
    }

    $case = CourtCase::with(['tribunal', 'department', 'judge'])
                     ->where('number', $request->case_number)
                     ->first();

    if (!$case) {
        Log::warning(' القضية غير موجودة', [
            'case_number' => $request->case_number,
            'request' => $request->all()
        ]);
        return response()->json(['error' => 'القضية غير موجودة'], 404);
    }

    Log::info(' تم العثور على القضية', ['case_id' => $case->id]);

    $participants = Participant::where('court_case_id', $case->id)->get();
    $judgeName = optional($case->judge)->full_name;

    $memo = ArrestMemo::where('case_id', $case->id)
                      ->latest()
                      ->first();

    if (!$memo) {
        Log::warning(' لا توجد مذكرة توقيف لهذه القضية', [
            'case_id' => $case->id,
            'case_number' => $request->case_number,
            'request' => $request->all()
        ]);
        return response()->json(['error' => 'لا توجد مذكرة توقيف لهذه القضية'], 404);
    }

    Log::info(' تم العثور على مذكرة التوقيف', ['memo_id' => $memo->id]);

    if ($request->has('save') && $request->save == true) {
        Log::info(' بدء عملية حفظ التمديد', [
            //تمديد الايام
            'memo_id' => $memo->id,
            'extension_days' => $request->extension_days
        ]);
         // تحديث البيانات
        try {
            $memo->detention_duration += $request->extension_days;
            $memo->detention_reason = $request->detention_reason;
            $memo->detention_center = $request->detention_center;
            $memo->participant_name = $request->participant_name;
            $memo->judge_name = $judgeName;
            $memo->updated_at = now();
            $memo->save();

            Log::info(' تم تمديد مذكرة التوقيف بنجاح', [
                'case_id' => $case->id,
                'memo_id' => $memo->id,
                'new_duration' => $memo->detention_duration,
                'request' => $request->all()
            ]);

            return response()->json(['message' => 'تم تمديد مدة التوقيف بنجاح']);
        } catch (\Exception $e) {
            Log::error(' خطأ أثناء تمديد مذكرة التوقيف', [
                'case_number' => $request->case_number,
                'judge_name'  => $judgeName,
                'participant_name' => $request->participant_name,
                'error'       => $e->getMessage(),
                'trace'       => $e->getTraceAsString(),
                'request'     => $request->all()
            ]);

            return response()->json(['error' => 'حدث خطأ أثناء التمديد'], 500);
        }
    }

    Log::info(' عرض بيانات القضية بدون حفظ', ['case_id' => $case->id]);

    return response()->json([
        'case'              => $case,
        'participants'      => $participants,
        'judge_name'        => $judgeName,
        'tribunal_number'   => optional($case->tribunal)->number,
        'department_number' => optional($case->department)->number,
        'current_duration'  => $memo->detention_duration,
    ]);
}














//المشاركين
// المشاركين — بحث الأحوال المدنية
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
    if ($request->filled('gender')) {
        $query->where('gender', $request->gender);
    }

    $results = $query->get()->map(function ($item) {
        // 🔹 إذا first_name فاضي لأي سبب، استخرجيه من full_name
        $firstName = $item->first_name;

        if (!$firstName && $item->full_name) {
            $parts = preg_split('/\s+/', trim($item->full_name));
            $firstName = $parts[0] ?? null;
        }

        return [
            'national_id'      => $item->national_id,
            'first_name'       => $firstName,               // ⭐ دايمًا بنرجّع قيمة
            'father_name'      => $item->father_name,
            'mother_name'      => $item->mother_name,
            'grandfather_name' => $item->grandfather_name,
            'family_name'      => $item->family_name,
            'birth_date'       => $item->birth_date,
            'age'              => $item->age,
            'gender'           => $item->gender,
            'religion'         => $item->religion,
            'nationality'      => $item->nationality,
            'place_of_birth'   => $item->place_of_birth,
            'occupation'       => $item->occupation,
            'education_level'  => $item->education_level,
            'phone_number'     => $item->phone_number,
            'record_location'  => $item->record_location,
        ];
    });

    return response()->json($results);
}


//مذكرة إفراج للموقوفين
public function storeReleaseMemo(Request $request)
{
    Log::info(' تم الوصول إلى دالة حفظ مذكرة الإفراج', [
        'timestamp' => now()->toDateTimeString(),
        'request' => $request->all()
    ]);

    $validated = $request->validate([
        'case_number' => 'required|string',
        'released_participants' => 'required|array',
        'released_participants.*' => 'string',
    ]);

    try {
        $cleanNumber = trim($validated['case_number']);

        Log::debug(' البحث عن القضية باستخدام رقم الدعوى:', [
            'رقم_الدعوى_المدخل' => $cleanNumber
        ]);

        $case = CourtCase::where('number', $cleanNumber)->with('judge')->first();

        Log::debug(' نتيجة البحث عن القضية:', [
            'case_found' => $case ? true : false,
            'case_id' => $case->id ?? null
        ]);

        if (!$case) {
            Log::error(' القضية غير موجودة في جدول court_cases', [
                'رقم_الدعوى' => $cleanNumber,
                'request' => $request->all()
            ]);

            return response()->json([
                'error' => 'القضية غير موجودة',
                'رقم_مدخل' => $cleanNumber,
                'ملاحظة' => 'تأكد من أن الرقم مطابق تمامًا لما هو موجود في قاعدة البيانات'
            ], 404);
        }

        $memo = ArrestMemo::where('case_id', $case->id)->latest()->first();

        Log::debug(' نتيجة البحث عن مذكرة التوقيف:', [
            'memo_found' => $memo ? true : false,
            'memo_id' => $memo->id ?? null
        ]);

        if (!$memo) {
            Log::error(' لا توجد مذكرة توقيف لهذه القضية', [
                'case_id' => $case->id,
                'request' => $request->all()
            ]);
            return response()->json(['error' => 'لا توجد مذكرة توقيف لهذه القضية'], 404);
        }

        $judgeName = optional($case->judge)->full_name ?? 'غير محدد';

        $memo->released = 'تم الإفراج';
        $memo->judge_name = $judgeName;
        $memo->updated_at = now();
        $memo->save();

        Log::info(' تم حفظ مذكرة الإفراج بنجاح', [
            'case_number' => $cleanNumber,
            'memo_id' => $memo->id,
            'released_participants' => $validated['released_participants']
        ]);

        return response()->json(['status' => 'success', 'memo_id' => $memo->id]);

    } catch (\Exception $e) {
        Log::error(' خطأ غير متوقع أثناء حفظ مذكرة الإفراج', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'request' => $request->all()
        ]);

        return response()->json(['error' => 'حدث خطأ أثناء حفظ مذكرة الإفراج'], 500);
    }
}
//مذكرة الإفراج عن الموقوفين
public function fetchCaseParticipants(Request $request)
{
    $caseNumber = $request->input('case_number');

    $courtCase = CourtCase::where('number', $caseNumber)->with('judge')->first();

    if (!$courtCase) {
        return response()->json(['error' => 'رقم الدعوى غير موجود'], 404);
    }

    $participants = Participant::where('court_case_id', $courtCase->id)->get();

    return response()->json([
        'case_type' => $courtCase->type,
        'judge_name' => optional($courtCase->judge)->full_name,
        'participants' => $participants,
    ]);
}
//مذكرة الإفراج عن الموقوفين
public function defaultInfo()
{
    $tribunal = Tribunal::first();
    $department = Department::first();

    return response()->json([
        'tribunal' => $tribunal,
        'department' => $department,
    ]);
}







//إدارة تباليغ الدعوى
public function getCaseNotifications($caseNumber)
{
    try {

        // 1 جلب القضية بناءً على رقم الدعوى الحقيقي (number)
        $case = CourtCase::with(['tribunal', 'department', 'judge'])->where('number', $caseNumber)->first();

        if (!$case) {
            return response()->json(['error' => 'رقم الدعوى غير موجود'], 404);
        }

        // 2 جلب التباليغ المرتبطة بالقضية
        $notifications = Notification::query()
            ->where('notifications.case_id', $case->id)
            ->leftJoin('participants', function ($join) {
                $join->on('participants.court_case_id', '=', 'notifications.case_id')
                     ->on('participants.name', '=', 'notifications.participant_name');
            })
            ->select([
                'notifications.participant_name',
                'notifications.method',
                'notifications.notified_at',
                'participants.type as participant_type'
            ])
            ->orderBy('notifications.notified_at', 'asc')
            ->get()
            ->map(function ($row) use ($case) {
                return [
                    'case_number'      => $case->number,
                    'participant_type' => $row->participant_type ?? 'غير محدد',
                    'participant_name' => $row->participant_name,
                    'method'           => $row->method,
                    'notified_at' => $row->notified_at
                    ? \Carbon\Carbon::parse($row->notified_at)->format('Y-m-d H:i')
                    : null,
                ];
            });

        // 3 جلب الأطراف
        $participants = Participant::where('court_case_id', $case->id)->get();

        // 4إرجاع البيانات كـ JSON
        return response()->json([
            'case_number'   => $case->number,
            'case_court'    => $case->tribunal->number ?? '',
            'case_pen'      => $case->department->number ?? '',
            'case_year'     => $case->year ?? '',
            'case_type'     => $case->type ?? '',
            'judge_name'    => optional($case->judge)->full_name ?? '',
            'participants'  => $participants,
            'notifications' => $notifications,
        ]);

    } catch (\Exception $e) {

        Log::error(' خطأ أثناء تحميل تباليغ الدعوى', [
            'message' => $e->getMessage(),
            'line'    => $e->getLine(),
            'file'    => $e->getFile(),
        ]);

        return response()->json(['error' => 'تعذر تحميل تباليغ الدعوى'], 500);
    }
}










//نافذه المذكرات
public function getArrestMemos($caseNumber)
{
    try {

        // 1️⃣ جلب القضية من رقم الدعوى الحقيقي (number)
        $case = CourtCase::where('number', $caseNumber)->first();

        if (!$case) {
            return response()->json([
                'error' => 'رقم الدعوى غير موجود'
            ], 404);
        }

        // 2️⃣ جلب مذكرات التوقيف الخاصة بهذه القضية
        $memos = DB::table('arrest_memos')
            ->where('case_id', $case->id)
            ->select(
                'participant_name',
                'released',
                'detention_duration',
                'detention_reason',
                'detention_center'
            )
            ->get()
            ->map(function ($row) use ($case) {
                return [
                    'case_number'        => $case->number,
                    'participant_name'   => $row->participant_name,
                    'released'           => $row->released ? 'نعم' : 'لا',
                    'detention_duration' => $row->detention_duration,
                    'detention_reason'   => $row->detention_reason,
                    'detention_center'   => $row->detention_center,
                ];
            });

        return response()->json([
            'case_number' => $case->number,
            'memos'       => $memos
        ]);

    } catch (\Exception $e) {

        Log::error('❌ خطأ أثناء جلب مذكرات التوقيف', [
            'message' => $e->getMessage(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine()
        ]);

        return response()->json([
            'error' => 'حدث خطأ أثناء تحميل البيانات'
        ], 500);
    }
}










//نافذة تسجيل طلب
public function storeRequest(Request $request)
{
    try {

        // 1) توليد رقم طلب من 4 خانات غير مكرر
        do {
            $randomNumber = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
            $exists = RequestSchedule::where('request_number', $randomNumber)->exists();
        } while ($exists);

        // 2) السنة الحالية
        $year = date('Y');

        // 3) المستخدم الحالي
        $user = auth()->user();

        // 4) رقم المحكمة والقلم
        $tribunalNumber   = $user->tribunal?->number ?? null;
        $departmentNumber = $user->department?->number ?? null;

        // 5) تحديد القاضي التالي
        $judges = User::where('role', 'judge')->orderBy('id')->get();
        $lastRequest = RequestSchedule::latest()->first();
        $lastJudgeId = $lastRequest?->judge_id;
        $nextJudge = $judges->firstWhere('id', '>', $lastJudgeId) ?? $judges->first();

        // 6) تحديد موعد الجلسة تلقائيًا
        $hourOptions = [[8, 30], [15, 30]];

        do {
            $daysAhead = random_int(1, 6);
            $sessionDay = now()->addDays($daysAhead);

            $chosenTime = $hourOptions[array_rand($hourOptions)];
            $sessionDate = $sessionDay->setTime($chosenTime[0], $chosenTime[1]);

            // التأكد إذا موجود نفس الموعد لنفس القاضي
            $exists = RequestSchedule::where('judge_id', $nextJudge->id)
                ->where('session_date', $sessionDate)
                ->exists();
        } while ($exists);

        // 7) إنشاء الطلب
        $requestSchedule = RequestSchedule::create([
            'request_number' => $randomNumber,
            'court_year'     => $year,
            'tribunal_id'    => $user->tribunal_id,
            'department_id'  => $user->department_id,
            'judge_id'       => $nextJudge->id,
            'session_date'   => $sessionDate,
            'session_time'   => $sessionDate->format('H:i'),
            'title'          => $request->type,

            // القيمة التي طلبتِها 
            'session_status' => 'محددة',
        ]);

        // 8) إرجاع البيانات لـ JS
        return response()->json([
            'id'               => $requestSchedule->id,
            'request_number'   => $randomNumber,
            'court_year'       => $year,
            'tribunal_number'  => $tribunalNumber,
            'department_number'=> $departmentNumber,
            'judge_id'         => $nextJudge->id,
            'judge_name'       => $nextJudge->full_name,
            'session_date'     => $sessionDate->format('Y-m-d H:i'),
        ]);

    } catch (\Exception $e) {

        Log::error(' خطأ أثناء إنشاء الطلب', [
            'message' => $e->getMessage(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
            'trace'   => $e->getTraceAsString(),
            'request' => $request->all(),
        ]);

        return response()->json(['error' => 'حدث خطأ أثناء إنشاء الطلب.'], 500);
    }
}

public function storeRequestParties(Request $request)
{
    try {
        $validated = $request->validate([
            'request_id'        => 'required|exists:request_schedules,id',
            'parties'           => 'required|array|min:1',
            'parties.*.type'    => 'required|string',
            'parties.*.name'    => 'required|string',
            'parties.*.national_id' => 'nullable|string',
            'parties.*.residence'   => 'nullable|string',
            'parties.*.job'         => 'nullable|string',
            'parties.*.phone'       => 'nullable|string',
        ]);

        $schedule = RequestSchedule::findOrFail($validated['request_id']);

        // اختياري: نفرّغ الحقول قبل ما نعبّيها من جديد
        $schedule->plaintiff_name            = null;
        $schedule->plaintiff_national_id     = null;
        $schedule->plaintiff_residence       = null;
        $schedule->plaintiff_job             = null;
        $schedule->plaintiff_phone           = null;

        $schedule->defendant_name            = null;
        $schedule->defendant_national_id     = null;
        $schedule->defendant_residence       = null;
        $schedule->defendant_job             = null;
        $schedule->defendant_phone           = null;

        $schedule->third_party_name          = null;
        $schedule->third_party_national_id   = null;
        $schedule->third_party_residence     = null;
        $schedule->third_party_job           = null;
        $schedule->third_party_phone         = null;

        $schedule->lawyer_name               = null;
        $schedule->lawyer_national_id        = null;
        $schedule->lawyer_residence          = null;
        $schedule->lawyer_job                = null;
        $schedule->lawyer_phone              = null;

        foreach ($validated['parties'] as $party) {

            switch ($party['type']) {

                case 'مشتكي':
                    $schedule->plaintiff_name          = $party['name'];
                    $schedule->plaintiff_national_id   = $party['national_id'] ?? null;
                    $schedule->plaintiff_residence     = $party['residence'] ?? null;
                    $schedule->plaintiff_job           = $party['job'] ?? null;
                    $schedule->plaintiff_phone         = $party['phone'] ?? null;
                    break;

                case 'مشتكى عليه':
                    $schedule->defendant_name          = $party['name'];
                    $schedule->defendant_national_id   = $party['national_id'] ?? null;
                    $schedule->defendant_residence     = $party['residence'] ?? null;
                    $schedule->defendant_job           = $party['job'] ?? null;
                    $schedule->defendant_phone         = $party['phone'] ?? null;
                    break;

                case 'شاهد':
                    $schedule->third_party_name        = $party['name'];
                    $schedule->third_party_national_id = $party['national_id'] ?? null;
                    $schedule->third_party_residence   = $party['residence'] ?? null;
                    $schedule->third_party_job         = $party['job'] ?? null;
                    $schedule->third_party_phone       = $party['phone'] ?? null;
                    break;

                case 'محامي':
                    $schedule->lawyer_name             = $party['name'];
                    $schedule->lawyer_national_id      = $party['national_id'] ?? null;
                    $schedule->lawyer_residence        = $party['residence'] ?? null;
                    $schedule->lawyer_job              = $party['job'] ?? null;
                    $schedule->lawyer_phone            = $party['phone'] ?? null;
                    break;
            }
        }

        $schedule->save();

        return response()->json(['message' => 'تم حفظ الأطراف بنجاح']);

    } catch (\Exception $e) {

        Log::error(' خطأ حفظ الأطراف في الطلب', [
            'message' => $e->getMessage(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
            'trace'   => $e->getTraceAsString(),
            'request' => $request->all(),
        ]);

        return response()->json(['error' => 'حدث خطأ أثناء حفظ الأطراف.'], 500);
    }
}


public function getNextJudgeForRequest()
{
    try {
        $judges      = User::where('role', 'judge')->orderBy('id')->get();
        $lastRequest = RequestSchedule::latest()->first();
        $lastJudgeId = $lastRequest?->judge_id;
        $nextJudge   = $judges->firstWhere('id', '>', $lastJudgeId) ?? $judges->first();

        return response()->json([
            'judge_id'  => $nextJudge->id,
            'full_name' => $nextJudge->full_name,
        ]);

    } catch (\Exception $e) {

        Log::error(' خطأ في getNextJudgeForRequest', [
            'message' => $e->getMessage(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
            'trace'   => $e->getTraceAsString(),
        ]);

        return response()->json(['error' => 'حدث خطأ أثناء جلب القاضي التالي.'], 500);
    }
}




























public function loadReportsList()
{
    try {

        Log::info(' بدء تحميل قائمة محاضر الجلسات للكاتب', [
            'writer_id' => auth()->id(),
        ]);

        $writer = auth()->user();

        //  1) جلب القضاة اللي الكاتب إله صلاحية عليهم
        $allowedJudges = \App\Models\JudgeUser::where('user_id', $writer->id)
                            ->pluck('judge_id')
                            ->toArray();

        Log::info(' القضاة المسموحين للكاتب', [
            'writer_id'      => $writer->id,
            'allowedJudges'  => $allowedJudges,
        ]);

        if (empty($allowedJudges)) {
            Log::warning('⚠️ لا يوجد قضاة مخصصون لهذا الكاتب', [
                'writer_id' => $writer->id,
            ]);

            return response()->json([
                'reports' => [],
                'message' => 'لا يوجد قضاة مخصصون لهذا الكاتب'
            ]);
        }

        //  جلب جلسات فيها محاضر + تجميع حسب الجلسة ونوع المحضر
        $sessions = CourtSessionReport::select('case_session_id', 'report_mode')
            ->groupBy('case_session_id', 'report_mode')
            ->get();

        Log::info('📄 عدد سجلات المحاضر المسترجعة من court_session_reports', [
            'count' => $sessions->count()
        ]);

        $result = [];

        foreach ($sessions as $record) {

            $session = CaseSession::with('courtCase')->find($record->case_session_id);

            if (!$session || !$session->courtCase) {
                Log::warning('⚠️ جلسة أو قضية غير موجودة أثناء بناء النتيجة', [
                    'case_session_id' => $record->case_session_id
                ]);
                continue;
            }

            $case = $session->courtCase;

            //  فلترة حسب القاضي المسند للكاتب
            if (!in_array($case->judge_id, $allowedJudges)) {
                continue;
            }

            //  تجهيز السطر
            if (!isset($result[$session->id])) {
                $result[$session->id] = [
                    'session_id' => $session->id,
                    'case' => [
                        'id'     => $case->id,
                        'number' => $case->number,
                        'type'   => $case->type,
                    ],
                    'modes' => []
                ];
            }

            $result[$session->id]['modes'][] = $record->report_mode;
        }

        Log::info(' تم تجهيز النتيجة لمحاضر الجلسات', [
            'writer_id' => $writer->id,
            'sessions_count' => count($result),
        ]);

        return response()->json([
            'reports' => array_values($result)
        ]);

    } catch (\Exception $e) {

        Log::error(' خطأ في loadReportsList', [
            'writer_id' => auth()->id(),
            'message'   => $e->getMessage(),
            'trace'     => $e->getTraceAsString(),
        ]);

        return response()->json([
            'reports' => [],
            'error'   => 'حدث خطأ أثناء تحميل محاضر الجلسات'
        ], 500);
    }
}



public function showRequestSchedule(Request $request)
{
    return app(\App\Http\Controllers\TypistController::class)->showRequestSchedule($request);
}

}
