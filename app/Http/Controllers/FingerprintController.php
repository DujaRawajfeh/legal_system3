<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CourtSessionReport;

class FingerprintController extends Controller
{
    public function save(Request $request)
    {
        try {
            $validated = $request->validate([
                'court_case_id'   => 'required|exists:court_cases,id',
                'case_session_id' => 'required|exists:case_sessions,id', 
                'participant_id'  => 'required|exists:participants,id',
                'fingerprint'     => 'required|string',
                'report_mode'     => 'required|in:trial,after',
            ]);

            $report = CourtSessionReport::where('case_session_id', $request->case_session_id)
                ->where('participant_id', $request->participant_id)
                ->where('report_mode', $request->report_mode)
                ->first();

            if ($report) {
                $report->update([
                    'fingerprint' => 'البصمة محفوظة',
                ]);
            } else {
                // إنشاء صف جديد إذا لم يوجد
                $report = CourtSessionReport::create([
                    'court_case_id'   => $request->court_case_id,
                    'case_session_id' => $request->case_session_id,
                    'participant_id'  => $request->participant_id,
                    'report_mode'     => $request->report_mode,
                    'fingerprint'     => 'البصمة محفوظة',
                ]);
            }

            return response()->json([
                'success' => true,
                'report_id' => $report->id,
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
