<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class DenyAccessIfBanned
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check() and Auth::user()->isRole('banned')) {
            Auth::user()->rules_accepted = false;
            Auth::user()->save();

            $id = Auth::id();
            $ban = Auth::user()->ban;
            Auth::logout();

            return redirect()->route('rules')
                ->with('alert.type', 'error')
                ->with('alert.title', 'ВЫ БЫЛИ ЗАБАНЕНЫ!')
                ->with('alert.message', "Вы были забанены {$ban->timestamp} за нарушение Правил сайта ({$ban->message}). Если вы хотите обжаловать бан, обратитесь в техподдержку (при обращении передайте ваш ID - {$id}.");
        }

        return $next($request);
    }
}
