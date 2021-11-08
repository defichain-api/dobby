<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WebAppAuthMiddleware
{
	public function handle(Request $request, Closure $next)
	{
		$webAppUserId = $request->header('x-user-auth', null);
		try {
			$webAppUser = User::findOrFail($webAppUserId);
		} catch (ModelNotFoundException $e) {
			return response()->json([
				'state'  => 'error',
				'reason' => 'not authorized',
			], Response::HTTP_UNAUTHORIZED);
		}
		$request->merge([
			'user' => $webAppUser,
		]);

		return $next($request);
	}
}
