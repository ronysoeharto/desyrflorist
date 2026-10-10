<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectCourierMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->isCourier() && ! $request->is('courier*') && ! $request->is('logout')) {
            return redirect()->route('courier.dashboard');
        }

        return $next($request);
    }
}
