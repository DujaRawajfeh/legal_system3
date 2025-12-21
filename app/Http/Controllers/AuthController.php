<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Str;




use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Writer;

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
        'password'    => 'required',
    ]);

    $user = User::where('national_id', $request->national_id)->first();

    if ($user) {
        if ($user && Hash::check($request->password, $user->password)) {

            

            //  تحقق من تفعيل المصادقة الثنائية
            if ($user->two_factor_enabled) {
                // نخزن المستخدم مؤقتًا لحين إدخال رمز TOTP
                session(['pending_2fa_user' => $user->id]);
                return redirect()->route('2fa.verify.form');
            }

            //  تسجيل الدخول العادي إذا ما في 2FA
            Auth::login($user);
            session(['active_role' => $user->role]);

            switch ($user->role) {
                case 'writer':   return redirect()->route('writer.dashboard');
                case 'chief':    return redirect()->route('chief.dashboard');
                case 'judge':    return redirect()->route('judge.index');
                case 'typist':   return redirect()->route('typist.index');
                case 'archiver': return redirect()->route('archiver.page');
                default:         return redirect('/');
            }
        }
    }

    return back()->withErrors([
        'error' => 'الرقم الوطني أو كلمة المرور غير صحيحة'
    ]);
}


public function updatePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required',
        'new_password' => [
            'required',
            'string',
            'min:8',
            'confirmed',
            'regex:/[a-z]/',      // حرف صغير
            'regex:/[A-Z]/',      // حرف كبير
            'regex:/[0-9]/',      // رقم
            'regex:/[@$!%*#?&]/', // رمز خاص
        ],
    ], [
        'new_password.min'   => 'كلمة السر يجب أن تكون ٨ أحرف على الأقل.',
        'new_password.regex' => 'كلمة السر يجب أن تحتوي على حرف صغير، حرف كبير، رقم، ورمز خاص.',
    ]);

    //  المستخدم الحالي (بدون رقم وطني)
    $user = auth()->user();

    //  التحقق من كلمة السر الحالية
    if (!Hash::check($request->current_password, $user->password)) {
        return back()->withErrors([
            'current_password' => 'كلمة السر الحالية غير صحيحة'
        ]);
    }

    //  تحديث كلمة السر وتاريخ التغيير
    $user->password = Hash::make($request->new_password);
    $user->password_changed_at = now();
    $user->save();

    //  إعادة تسجيل الدخول
    Auth::login($user);
    session(['active_role' => $user->role]);

    //  التوجيه حسب الدور
    switch ($user->role) {
        case 'writer':
            return redirect()->route('writer.dashboard')
                ->with('success', 'تم تغيير كلمة السر بنجاح');

        case 'chief':
            return redirect()->route('chief.dashboard')
                ->with('success', 'تم تغيير كلمة السر بنجاح');

        case 'judge':
            return redirect()->route('judge.index')
                ->with('success', 'تم تغيير كلمة السر بنجاح');

        case 'typist':
            return redirect()->route('typist.index')
                ->with('success', 'تم تغيير كلمة السر بنجاح');

        case 'archiver':
            return redirect()->route('archiver.page')
                ->with('success', 'تم تغيير كلمة السر بنجاح');

        default:
            return redirect('/')
                ->with('success', 'تم تغيير كلمة السر بنجاح');
    }
}



















public function show2FASetup()
{
    $user = auth()->user();
    $google2fa = new \PragmaRX\Google2FA\Google2FA();

    if (!$user->two_factor_secret) {
        $user->two_factor_secret = $google2fa->generateSecretKey(32);
        $user->save();
    }

    $qrContent = $google2fa->getQRCodeUrl(
    'LegalSystem',          // ثابت كاسم النظام
    $user->national_id,     // ثابت يظهر في التطبيق
    $user->two_factor_secret
);

    $renderer = new \BaconQrCode\Renderer\ImageRenderer(
        new \BaconQrCode\Renderer\RendererStyle\RendererStyle(300),
        new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
    );
    $writer = new \BaconQrCode\Writer($renderer);
    $qrSvg = $writer->writeString($qrContent);

    return view('auth.2fa-setup', [
    'qrSvg'  => $qrSvg,
    'secret' => $user->two_factor_secret,
    'enabled'=> $user->two_factor_enabled,
]);
}

public function enable2FA(Request $request)
{
    $request->validate(['totp_code' => 'required|string']);
    $user = auth()->user();

    $google2fa = new Google2FA();
    $isValid = $google2fa->verifyKey($user->two_factor_secret, trim($request->totp_code));

    if (!$isValid) {
        return back()->withErrors(['totp_code' => 'الرمز غير صحيح']);
    }

    // توليد رموز احتياطية
    $recovery = [];
    for ($i = 0; $i < 8; $i++) {
        $recovery[] = Str::upper(Str::random(10));
    }

    $user->two_factor_enabled = true;
    $user->two_factor_recovery_codes = $recovery;
    $user->save();

    return redirect()->route('2fa.setup')->with('success', 'تم تفعيل المصادقة الثنائية.');
}


public function disable2FA()
{
    $user = auth()->user();

    $user->two_factor_enabled = false;
    $user->two_factor_secret = null;
    $user->two_factor_recovery_codes = null;
    $user->save();

    return back()->with('success', 'تم تعطيل المصادقة الثنائية.');
}

public function show2FAVerify()
{
    if (!session('pending_2fa_user')) {
        return redirect('/')->withErrors(['error' => 'جلسة التحقق غير صالحة.']);
    }
    return view('auth.2fa-verify');
}



public function verify2FA(Request $request)
{
    $request->validate(['totp_code' => 'required|string']);
    $userId = session('pending_2fa_user');
    $user = \App\Models\User::find($userId);

    if (!$user || !$user->two_factor_enabled) {
        return redirect('/')->withErrors(['error' => 'جلسة التحقق غير صالحة.']);
    }

    $google2fa = new \PragmaRX\Google2FA\Google2FA();
    $code = strtoupper(trim($request->totp_code));
    $isValid = $google2fa->verifyKey($user->two_factor_secret, $code);

    if (!$isValid) {
        return back()->withErrors(['totp_code' => 'الرمز غير صحيح.']);
    }

    // نجاح → كمّلي الدخول
    session()->forget('pending_2fa_user');

    Auth::login($user); //  استخدمي Auth::login بدل loginUsingId

    $request->session()->regenerate(); // تثبيت الجلسة

    session(['active_role' => $user->role]);

    return $this->redirectByRole($user);
}






protected function redirectByRole($user)
{
    switch ($user->role) {

        case 'writer':
            return redirect()->route('writer.dashboard');

        case 'typist':
            return redirect()->route('typist.index');

        case 'chief':
            return redirect()->route('chief.dashboard');

        case 'judge':
            return redirect()->route('judge.index'); 


         case 'archiver':
            return redirect()->route('archiver.page'); 


        default:
            abort(403); 
    }
}





public function logout(Request $request)
{
    Auth::logout();                
    $request->session()->invalidate(); 
    $request->session()->regenerateToken(); 

    return redirect()->route('login');
}
}