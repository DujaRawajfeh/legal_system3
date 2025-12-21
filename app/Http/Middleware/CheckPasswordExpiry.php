<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;

class CheckPasswordExpiry
{
    public function handle($request, Closure $next)
    {
        $user = $request->user();

        // استثناء صفحة تغيير كلمة السر نفسها
        if ($request->routeIs('password.change', 'password.update')) {
            return $next($request);
        }

        $lastChange = $user->password_changed_at
            ? Carbon::parse($user->password_changed_at)
            : Carbon::parse($user->created_at);

        if ($lastChange->diffInDays(now()) >= 90) {
            return redirect()->route('password.change');
        }

        return $next($request);
    }
}