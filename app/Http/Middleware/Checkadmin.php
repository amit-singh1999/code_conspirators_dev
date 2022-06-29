<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Checkadmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user()->role;
        $checkarray=array("Admin","Strategist","Operative");
        if (!in_array($user,$checkarray))
        {
          return redirect()->back();
        }
        return $next($request);
    }

}