<?php

use App\Api\Controller\NotificationGatewayController;
use App\Api\Controller\SetupController;
use App\Api\Controller\UserController;
use App\Api\Controller\VaultController;
use Illuminate\Support\Facades\Route;

Route::post('setup', [SetupController::class, 'setup'])
	->name('setup');

Route::middleware(['webapp_auth'])->group(function () {
	/**
	 * user routes
	 */
	Route::name('user.')->prefix('user')->group(function () {
		Route::get('/', [UserController::class, 'getUser'])
			->name('get');
		Route::delete('/', [UserController::class, 'deleteUser'])
			->name('delete');
	});

	/**
	 * vault routes
	 */
	Route::name('vault.')->prefix('user/vault')->group(function () {
		Route::post('/', [VaultController::class, 'createUserVault'])
			->name('create');
		Route::delete('/', [VaultController::class, 'deleteUserVault'])
			->name('delete');
	});

	/**
	 * notification gateway routes
	 */
	Route::name('notification_gateway.')->prefix('user/gateways')->group(function () {
		Route::get('/', [NotificationGatewayController::class, 'getGateways'])
			->name('get');
		Route::post('/', [NotificationGatewayController::class, 'createGateway'])
			->name('create');
		Route::delete('/', [NotificationGatewayController::class, 'deleteGateway'])
			->name('delete');
	});
});
