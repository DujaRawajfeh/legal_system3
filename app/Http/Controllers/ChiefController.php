<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CourtCase;
use App\Models\JudgeUser;
use App\Models\ArrestMemo;
use Carbon\Carbon;
use App\Models\RequestSchedule;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Log;
use App\Models\IncomingProsecutorCase;

class ChiefController extends Controller
{
    
   public function dashboard()
{
    // المستخدم الحالي
    $chief = Auth::user()->load(['tribunal', 'department']);

    //  متغير ضروري لأن writer.blade.php يستخدمه
    $records = IncomingProsecutorCase::select('records')
        ->distinct()
        ->whereNotNull('records')
        ->orderBy('records')
        ->get();

    return view('clerk_dashboard.chief', compact('chief', 'records'));
}

    //نافذه تحويل دعوى
   public function getJudges()
{
    try {
        $judges = User::where('role', 'judge')
                      ->orderBy('full_name')
                      ->get(['id', 'full_name']);

        return response()->json([
            'judges' => $judges
        ]);

    } catch (\Exception $e) {

        \Log::error(" خطأ getJudges:", [
            'message' => $e->getMessage(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
        ]);

        return response()->json(['error' => 'server_error'], 500);
    }
}

    // تحويل الدعوى من قاضي إلى آخر
    public function transferCase(Request $request)
    {
        $validated = $request->validate([
            'old_judge_id' => 'required|integer|exists:users,id',
            'new_judge_id' => 'required|integer|exists:users,id',
            'case_number'  => 'required|string',
        ]);

        // البحث عن القضية
        $courtCase = CourtCase::where('number', $validated['case_number'])
                              ->where('judge_id', $validated['old_judge_id'])
                              ->first();

        if (!$courtCase) {
            return response()->json([
                'error' => 'لم يتم العثور على دعوى بهذه البيانات أو لا تتبع هذا القاضي'
            ], 404);
        }

        // تنفيذ التحويل
        $courtCase->judge_id = $validated['new_judge_id'];
        $courtCase->save();

        return response()->json([
            'message' => '✔ تم تحويل الدعوى بنجاح',
            'case'    => $courtCase
        ]);
    }











// نافذة تعيين قاضي
public function getUsersAndJudges(Request $request)
{
    try {

        $role = $request->role;

        // المستخدمين حسب الدور
        $users = User::where('role', $role)->orderBy('full_name')->get();

        // القضاة
        $judges = User::where('role', 'judge')->orderBy('full_name')->get();

        return response()->json([
            'users'  => $users,
            'judges' => $judges,
        ]);

    } catch (\Exception $e) {

        Log::error("❌ خطأ داخل getUsersAndJudges:", [
            'message' => $e->getMessage(),
            'line'    => $e->getLine(),
            'file'    => $e->getFile(),
            'trace'   => $e->getTraceAsString(),
        ]);

        return response()->json(['error' => 'حدث خطأ أثناء تحميل البيانات'], 500);
    }
}

// حفظ التعيين
public function saveAssignment(Request $request)
{
    try {

        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'judge_id'    => 'required|exists:users,id',
        ]);

        JudgeUser::updateOrCreate(
            ['user_id' => $request->employee_id],
            ['judge_id' => $request->judge_id]
        );

        return response()->json([
            'message' => 'تم حفظ التعيين بنجاح'
        ]);

    } catch (\Exception $e) {

        Log::error(" خطأ داخل saveAssignment:", [
            'data_sent' => $request->all(),
            'message'   => $e->getMessage(),
            'line'      => $e->getLine(),
            'file'      => $e->getFile(),
            'trace'     => $e->getTraceAsString(),
        ]);

        return response()->json(['error' => 'خطأ أثناء الحفظ'], 500);
    }
}



// جلب المستخدمين حسب الدور
public function getEmployees(Request $request)
{
    try {

        $role = $request->query('role');

        if (!$role) {
            return response()->json(['error' => 'Role is required'], 400);
        }

        $users = User::where('role', $role)->get(['id','full_name']);

        return response()->json([
            'users' => $users
        ]);

    } catch (\Exception $e) {

        Log::error(" خطأ داخل getEmployees:", [
            'role'     => $request->role,
            'message'  => $e->getMessage(),
            'line'     => $e->getLine(),
            'file'     => $e->getFile(),
            'trace'    => $e->getTraceAsString(),
        ]);

        return response()->json(['error' => 'خطأ أثناء تحميل الموظفين'], 500);
    }
}












public function getDetainedList()
{
    try {

        $allMemos = ArrestMemo::with(['case'])
            ->where(function($query) {
                $query->where('released', 0)
                      ->orWhereNull('released');
            })
            ->get();

        $data = $allMemos->map(function ($item) {

                $start = $item->created_at->format('Y-m-d');

                $duration = intval($item->detention_duration);

                $end = \Carbon\Carbon::parse($start)->addDays($duration)->format('Y-m-d');

                $remaining = \Carbon\Carbon::now()->diffInDays($end, false);

                return [
                    'participant_name' => $item->participant_name,
                    'start_date'       => $start,
                    'duration'         => $duration,
                    'end_date'         => $end,
                    'remaining_days'   => $remaining,
                    'case_number'      => optional($item->case)->number,
                    'case_type'        => optional($item->case)->type,
                ];
            });

        return response()->json(['data' => $data]);

    } catch (\Exception $e) {

        Log::error("Error in getDetainedList", [
            'message' => $e->getMessage(),
            'line'    => $e->getLine(),
            'file'    => $e->getFile()
        ]);

        return response()->json(['error' => 'خطأ أثناء تحميل البيانات'], 500);
    }
}




//رقم الدعوى الشريط الثالث
public function loadRequestDetails(Request $request)
{
    try {
        $requestNumber = $request->input('request_number');

        if (!$requestNumber) {
            return response()->json([
                'success' => false,
                'message' => 'رقم الطلب مفقود',
            ], 400);
        }

        $req = RequestSchedule::with('judge')
            ->where('request_number', $requestNumber)
            ->first();

        if (!$req) {
            return response()->json([
                'success' => false,
                'message' => 'لم يتم العثور على طلب بهذا الرقم',
            ], 404);
        }

        return response()->json([
            'success' => true,

            //  معلومات الطلب
            'info' => [
                'request_number' => $req->request_number,
                'title'          => $req->title,
                'original_date'  => optional($req->created_at)->format('Y-m-d'),
                'judge_name'     => optional($req->judge)->full_name,
            ],

            //  الجلسات (حتى لو جلسة وحدة)
            'sessions' => [
                [
                    'date'   => $req->session_date,
                    'time'   => $req->session_time,
                    'goal'   => $req->session_purpose,
                    'reason' => $req->session_reason,
                ]
            ],

            //  الأطراف
            'parties' => array_filter([
                $req->plaintiff_name ? [
                    'type' => 'مشتكي',
                    'name' => $req->plaintiff_name
                ] : null,

                $req->defendant_name ? [
                    'type' => 'مشتكى عليه',
                    'name' => $req->defendant_name
                ] : null,

                $req->third_party_name ? [
                    'type' => 'طرف ثالث',
                    'name' => $req->third_party_name
                ] : null,

                $req->lawyer_name ? [
                    'type' => 'محامي',
                    'name' => $req->lawyer_name
                ] : null,
            ])
        ]);

    } catch (\Throwable $e) {

        \Log::error('❌ loadRequestDetails error', [
            'msg'  => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile(),
        ]);

        return response()->json([
            'success' => false,
            'message' => 'خطأ في السيرفر',
        ], 500);
    }
}
public function loadCaseDetails(Request $request)
{
    try {

        $caseNumber = $request->input('case_number');

        if (!$caseNumber) {
            return response()->json([
                'success' => false,
                'message' => 'رقم الدعوى مفقود'
            ], 400);
        }

        // جلب القضية
        $case = \App\Models\CourtCase::with(['participants', 'sessions'])
                ->where('number', $caseNumber)
                ->first();

        if (!$case) {
            return response()->json([
                'success' => false,
                'message' => 'لا يوجد دعوى بهذا الرقم'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'case' => [
                'number'       => $case->number,
                'title'        => $case->type,
                'original_date'=> $case->created_at->format('Y-m-d'),

                'sessions' => $case->sessions->map(function ($s) {
    return [
        'id'     => $s->id,
        'time'   => $s->session_time 
                        ? Carbon::parse($s->session_time)->format('H:i') 
                        : '-',

        'date'   => $s->session_date
                        ? Carbon::parse($s->session_date)->format('Y-m-d') 
                        : '-',

        'reason' => $s->session_goal ?? '-',
        'status' => $s->status ?? '-',
        'session_status' => $s->session_status ?? '-',
    ];
}),

                'participants' => $case->participants->map(function ($p) {
                    return [
                        'type'  => $p->type,
                        'name'  => $p->name,
                        'charge'=> $p->charge
                    ];
                }),
            ]
        ]);

    } catch (\Throwable $e) {

        \Log::error("❌ loadCaseDetails Error", [
            "msg" => $e->getMessage(),
            "line" => $e->getLine(),
            "file" => $e->getFile()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'خطأ في السيرفر'
        ], 500);
    }
}
}