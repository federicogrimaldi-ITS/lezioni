<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Support\UserSession;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! UserSession::isLoggedIn()) {
            return redirect()->route('login.form');
        }

        return $next($request);
    }
}