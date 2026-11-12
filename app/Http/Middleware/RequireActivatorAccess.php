<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireActivatorAccess
{
    /**
     * Only let requests through if the event access code was entered in this browser
     * (or no access code is configured, or the admin is logged in).
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(db4scw_has_activator_access())
        {
            return $next($request);
        }

        //remember where the user came from and ask for the access code
        session()->put('url.intended', url()->previous());

        return redirect()->route('access')->with('warning', 'Please enter the event access code first.');
    }
}
