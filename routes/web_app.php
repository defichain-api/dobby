<?php

use App\Api\Controller\LanguageController;
use App\Api\Controller\ListController;
use App\Api\Controller\NotificationGatewayController;
use App\Api\Controller\NotificationTriggerController;
use App\Api\Controller\PriceTickerController;
use App\Api\Controller\SetupController;
use App\Api\Controller\StatisticController;
use App\Api\Controller\UserController;
use App\Api\Controller\VaultController;
use Illuminate\Support\Facades\Route;

Route::name('language.')->prefix('language')->group(function () {
	Route::get('/', [LanguageController::class, 'languageList'])
		->name('list');
	Route::get('{iso}', [LanguageController::class, 'languageIso'])
		->name('iso');
});

Route::name('list.')->prefix('list')->group(function () {
	Route::get('timezones', [ListController::class, 'timezones'])
		->name('timezones');
	Route::get('summary_interval', [ListController::class, 'summaryInterval'])
		->name('summary_interval');
});

Route::post('setup', [SetupController::class, 'setup'])
	->name('setup');
Route::get('statistics', [StatisticController::class, 'getStatistics'])
	->name('statistics');

Route::get('price_ticker', [PriceTickerController::class, 'getNextTick'])
	->name('vault.ticker');

Route::middleware(['webapp_auth'])->group(function () {
	/**
	 * user routes
	 */
	Route::name('user.')->prefix('user')->group(function () {
		Route::get('/', [UserController::class, 'getUser'])
			->name('get');
		Route::put('settings', [UserController::class, 'updateUserSetting'])
			->name('update');
		Route::delete('/', [UserController::class, 'deleteUser'])
			->middleware('uneditable_demo')
			->name('delete');
	});

	/**
	 * vault routes
	 */
	Route::name('vault.')->prefix('user/vault')->group(function () {
		Route::post('/', [VaultController::class, 'createUserVault'])
			->middleware('uneditable_demo')
			->name('create');
		Route::put('{vault}', [VaultController::class, 'updateUserVaultName'])
			->middleware('uneditable_demo')
			->name('update');
		Route::delete('/', [VaultController::class, 'deleteUserVault'])
			->middleware('uneditable_demo')
			->name('delete');
	});

	/**
	 * notification gateway routes
	 */
	Route::name('notification_gateway.')->prefix('user/gateways')->group(function () {
		Route::get('/', [NotificationGatewayController::class, 'getGateways'])
			->name('get');
		Route::post('/', [NotificationGatewayController::class, 'createGateway'])
			->middleware('uneditable_demo')
			->name('create');
		Route::post('test', [NotificationGatewayController::class, 'testGateway'])
			->middleware('uneditable_demo')
			->name('test');
		Route::delete('/', [NotificationGatewayController::class, 'deleteGateway'])
			->middleware('uneditable_demo')
			->name('delete');
	});

	/**
	 * notification trigger routes
	 */
	Route::name('notification_trigger.')->prefix('user/notification')->group(function () {
		Route::get('/', [NotificationTriggerController::class, 'getTrigger'])
			->name('get');
		Route::post('/', [NotificationTriggerController::class, 'createTrigger'])
			->middleware('uneditable_demo')
			->name('create');
		Route::put('/', [NotificationTriggerController::class, 'updateTrigger'])
			->middleware('uneditable_demo')
			->name('update');
		Route::delete('/', [NotificationTriggerController::class, 'deleteTrigger'])
			->middleware('uneditable_demo')
			->name('delete');
	});
});
