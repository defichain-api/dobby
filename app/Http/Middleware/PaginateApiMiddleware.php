<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PaginateApiMiddleware
{
	public function handle(Request $request, Closure $next)
	{
		$response = $next($request);

		$data = rescue(fn() => $response->getData(true), null, false);

		if (is_null($data)) {
			return $response;
		}

		if (isset($data['meta']['links'])) {
			unset($data['meta']['links']);
		}

		$response->setData($data);

		return $response;
	}
}
