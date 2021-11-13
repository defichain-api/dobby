<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UneditableDemoMiddleware
{
	public function handle(Request $request, Closure $next)
	{
		/** @var \App\Models\User $user */
		$user = $request->get('user', null);

		if (isset($user) && \Str::contains($user->id(), 'demo')) {
			return response()->json([
				'state'   => 'error',
				'message' => 'demo data cant be modified',
			], Response::HTTP_BAD_REQUEST);
		}

		return $next($request);
	}
}
