<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarketerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('marketer')->check()) {
            return redirect()->route('marketer.login');
        }

        $user = Auth::guard('marketer')->user();

        if ($user->user_type !== 'marketer' || $user->status !== 'active') {
            Auth::guard('marketer')->logout();
            return redirect()->route('marketer.login')
                ->withErrors(['email' => 'حسابك غير مفعل أو تم إيقافه.']);
        }

        return $next($request);
    }
}
