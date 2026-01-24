<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CheckCookie
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Cookie::get('cookie_id')){
            $cookie_id = Session::getId();
            Cookie::queue(Cookie::make('cookie_id', $cookie_id, 60 * 24 * 30)); // تخزين cookie_id لمدة 30 يومًا
        }
        return $next($request);
    }
}
