<?php

namespace App\Api\Controller;

use App\Enum\NotificationGatewayType;
use App\Http\Resources\DepositResource;
use App\Http\Resources\PaymentResource;
use App\Models\Service\UserBalanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PaymentController
{
	public function getState(Request $request, UserBalanceService $userBalanceService): JsonResponse
	{
		/** @var \App\Models\User $user */
		$user = $request->get('user');

		$phoneNumber = $user->gateways()->where('type', NotificationGatewayType::PHONE)->first()?->value;

		return response()->json([
			'balanceDfi'     => $userBalanceService->forUser($user)->accountBalance(),
			'phoneNumber'    => $phoneNumber ?? 'not set',
			'canReceiveCall' => $userBalanceService->forUser($user)->canPayAmount(config('twilio.phone_call_cost'))
				&& isset($phoneNumber),
		], Response::HTTP_OK);
	}

	public function getTransactions(Request $request, UserBalanceService $userBalanceService): JsonResponse
	{
		/** @var \App\Models\User $user */
		$user = $request->get('user');
		$user->load('payments');
		ray([$user, $user->deposits(), $user->payments]);

		return response()->json([
			'balanceDfi' => $userBalanceService->forUser($user)->accountBalance(),
			'payments'   => PaymentResource::collection($user->payments),
			'deposits'   => DepositResource::collection($user->deposits()),
		], Response::HTTP_OK);
	}
}
