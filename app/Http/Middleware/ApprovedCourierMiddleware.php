<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApprovedCourierMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->isCourier()) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        if (! $user->isApprovedCourier()) {
            return redirect()->route('courier.pending');
        }

        return $next($request);
    }
}
