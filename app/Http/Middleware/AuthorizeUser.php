<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeUser
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        $user = $request->user();
        $user_role = $user->getRole();

        if (in_array($user_role, $roles)) {
            return $next($request);
        }

        abort(403, 'Forbidden. Kamu tidak punya akses ke halaman ini');
    }
}