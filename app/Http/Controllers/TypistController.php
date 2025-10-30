<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tribunal;
use App\Models\Department;


class TypistController extends Controller
{
   public function index()
{
    $user = auth()->user();

    $tribunal = Tribunal::find($user->tribunal_id);
    $department = Department::find($user->department_id);

    return view('clerk_dashboard.typist', [
        'tribunalName' => $tribunal->name ?? '---',
        'tribunalNumber' => $tribunal->number ?? '---',
        'departmentName' => $department->name ?? '---',
        'departmentNumber' => $department->number ?? '---',
        'userName' => $user->full_name,
    ]);
}
}
