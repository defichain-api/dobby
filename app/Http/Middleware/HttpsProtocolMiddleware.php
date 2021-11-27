<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HttpsProtocolMiddleware
{
	public function handle($request, Closure $next)
	{
		if (!$request->secure() && app()->environment(['production', 'stage'])) {
			return redirect()->secure($request->getRequestUri());
		}

		return $next($request);
	}
}
