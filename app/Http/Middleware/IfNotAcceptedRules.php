<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class IfNotAcceptedRules
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     * @internal param bool $rules
     */
    public function handle($request, Closure $next)
    {
        $excepts = [
            'rules', 'logout', 'user/settings', 'user/changeSettings',
        ];

        if (Auth::check() and !Auth::user()->rules_accepted) {
            if (!in_array($request->decodedPath(), $excepts)) {
                return redirect()->route('rules')->with('rules_accepted', false);
            };
        };

        return $next($request);
    }
}
