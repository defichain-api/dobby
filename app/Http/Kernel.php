<?php

namespace App\Http;

use App\Http\Middleware\EncryptCookies;
use App\Http\Middleware\HorizonBasicAuthMiddleware;
use App\Http\Middleware\HttpsProtocolMiddleware;
use App\Http\Middleware\PreventRequestsDuringMaintenance;
use App\Http\Middleware\TrimStrings;
use App\Http\Middleware\TrustProxies;
use App\Http\Middleware\UneditableDemoMiddleware;
use App\Http\Middleware\VerifyCsrfToken;
use App\Http\Middleware\WebAppAuthMiddleware;
use Fruitcake\Cors\HandleCors;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Illuminate\Http\Middleware\SetCacheHeaders;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class Kernel extends HttpKernel
{
	protected $middleware = [
		TrustProxies::class,
		HandleCors::class,
		PreventRequestsDuringMaintenance::class,
		ValidatePostSize::class,
		TrimStrings::class,
		ConvertEmptyStringsToNull::class,
	];
	protected $middlewareGroups = [
		'web' => [
			EncryptCookies::class,
			AddQueuedCookiesToResponse::class,
			StartSession::class,
			ShareErrorsFromSession::class,
			VerifyCsrfToken::class,
			SubstituteBindings::class,
			HttpsProtocolMiddleware::class,
		],

		'api' => [
			//			'throttle:api',
			HandleCors::class,
			SubstituteBindings::class,
			HttpsProtocolMiddleware::class,
		],
	];
	protected $routeMiddleware = [
		'cache.headers'    => SetCacheHeaders::class,
		'throttle'         => ThrottleRequests::class,
		'webapp_auth'      => WebAppAuthMiddleware::class,
		'uneditable_demo'  => UneditableDemoMiddleware::class,
		'horizonBasicAuth' => HorizonBasicAuthMiddleware::class,
	];
}
