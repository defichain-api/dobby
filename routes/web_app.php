<?php

use App\Api\Controller\LanguageController;
use App\Api\Controller\NotificationGatewayController;
use App\Api\Controller\NotificationTriggerController;
use App\Api\Controller\SetupController;
use App\Api\Controller\UserController;
use App\Api\Controller\VaultController;
use Illuminate\Support\Facades\Route;

Route::name('language.')->prefix('language')->group(function () {
	Route::get('/', [LanguageController::class, 'languageList'])
		->name('list');
	Route::get('{iso}', [LanguageController::class, 'languageIso'])
		->name('iso');
});

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

	/**
	 * notification trigger routes
	 */
	Route::name('notification_trigger.')->prefix('user/notification')->group(function () {
		Route::get('/', [NotificationTriggerController::class, 'getTrigger'])
			->name('get');
		Route::post('/', [NotificationTriggerController::class, 'createTrigger'])
			->name('create');
		Route::put('/', [NotificationTriggerController::class, 'updateTrigger'])
			->name('update');
		Route::delete('/', [NotificationTriggerController::class, 'deleteTrigger'])
			->name('delete');
	});
});
