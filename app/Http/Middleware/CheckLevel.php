<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\User;
class CheckLevel
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        
        $user = Auth::user();
        if (isset($user)) {
            if ($user->position->level > env('ADMIN_LEVEL')) {
                return redirect()->route('home');
            }
        }
        return $next($request);
    }
}
