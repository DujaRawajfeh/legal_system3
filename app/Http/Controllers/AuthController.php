<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
{
    $request->validate([
        'national_id' => 'required',
        'password' => 'required',
    ]);

    $user = User::where('national_id', $request->national_id)->first();

    if ($user) {
        // مقارنة نصية دقيقة
        if (trim($user->password) === trim($request->password)) {
            Auth::login($user);

            if ($user->role === 'writer') {
                return redirect()->route('writer.dashboard');
            }

            return redirect('/');
        }
    }

    return back()->withErrors(['error' => 'الرقم الوطني أو كلمة المرور غير صحيحة']);
}
}