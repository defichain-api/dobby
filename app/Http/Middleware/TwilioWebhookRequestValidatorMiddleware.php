<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Twilio\Security\RequestValidator;

class TwilioWebhookRequestValidatorMiddleware
{
	/**
	 * @param \Illuminate\Http\Request $request
	 * @param \Closure                 $next
	 *
	 * @return \Illuminate\Http\JsonResponse|mixed
	 */
	public function handle(Request $request, Closure $next)
	{
		$validator = new RequestValidator(config('twilio.auth_token'));
		$requestData = $request->toArray();

		// Switch to the body content if this is a JSON request.
		if (array_key_exists('bodySHA256', $requestData)) {
			$requestData = $request->getContent();
		}
		if (!$validator->validate(
			$request->header('x-twilio-signature'),
			$request->fullUrl(),
			$requestData)) {
			return response()->json(['message' => 'You are not Twilio :('], Response::HTTP_FORBIDDEN);
		}

		return $next($request);
	}
}
