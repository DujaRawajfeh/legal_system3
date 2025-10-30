<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WriterController;
use App\Http\Controllers\TypistController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);



Route::get('/writer/dashboard', [WriterController::class, 'dashboard'])->middleware('auth')->name('writer.dashboard');
Route::post('/court-cases/store', [WriterController::class, 'storeCourtCase'])->name('courtCases.store');
Route::post('/participants/store', [WriterController::class, 'storeParticipant']);
Route::get('/writer/get-next-available-judge', [WriterController::class, 'getNextAvailableJudge']);
Route::get('/cases/{id}', [WriterController::class, 'show'])->name('cases.show');
Route::get('/court-cases/{number}', [WriterController::class, 'fetchCaseDetails']);
Route::post('/notifications/save', [WriterController::class, 'saveNotification'])->name('notifications.save');
Route::post('/cases/pull', [WriterController::class, 'pullFromModal'])->name('cases.pull');




Route::post('/writer/pull-police-case/{id}', [WriterController::class, 'pullFromPoliceCase'])->name('writer.pull-police-case');
Route::get('/writer/assign-judge/{departmentId}', [WriterController::class, 'assignJudge'])->name('writer.assign-judge');
Route::get('/police-cases/by-center/{center}', [WriterController::class, 'getPoliceCasesByCenter']);





Route::post('/writer/arrest-memo', [WriterController::class, 'handleArrestMemo']);
Route::post('/writer/save-extend-arrest-memo', [WriterController::class, 'saveExtendArrestMemo']);
Route::get('/writer/dashboard', [WriterController::class, 'showFilteredSessions'])->name('writer.dashboard');
Route::get('/writer/search-civil', [WriterController::class, 'searchCivilRegistry'])->name('participants.search');
// ✅ جلب بيانات الدعوى والمشاركين للنافذة
Route::get('/release-memo/fetch', [WriterController::class, 'fetchReleaseMemoData'])->name('release.memo.fetch');

// ✅ حفظ مذكرة الإفراج
Route::post('/release-memo/store', [WriterController::class, 'storeReleaseMemo'])->name('release.memo.store');







Route::get('/typist', [TypistController::class, 'index'])->name('typist.index');