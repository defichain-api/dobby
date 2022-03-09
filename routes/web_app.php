<?php

use App\Api\Controller\BroadcastMessageController;
use App\Api\Controller\LanguageController;
use App\Api\Controller\ListController;
use App\Api\Controller\NotificationGatewayController;
use App\Api\Controller\NotificationTriggerController;
use App\Api\Controller\PaymentController;
use App\Api\Controller\PriceTickerController;
use App\Api\Controller\SetupController;
use App\Api\Controller\StatisticController;
use App\Api\Controller\UserController;
use App\Api\Controller\VaultController;
use Illuminate\Support\Facades\Route;

Route::name('language.')
	->prefix('language')
	->controller(LanguageController::class)
	->group(function () {
		Route::get('/', 'languageList')
			->name('list');
		Route::get('{iso}', 'languageIso')
			->name('iso');
	});

Route::name('list.')
	->prefix('list')
	->controller(ListController::class)
	->group(function () {
		Route::get('timezones', 'timezones')
			->name('timezones');
		Route::get('summary_interval', 'summaryInterval')
			->name('summary_interval');
	});

Route::name('broadcast.')
	->prefix('broadcast')
	->controller(BroadcastMessageController::class)
	->group(function () {
		Route::get('list', 'currentMessages')->name('list');
		Route::get('history', 'history')->name('history');
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
	Route::name('user.')
		->prefix('user')
		->controller(UserController::class)
		->group(function () {
			Route::get('/', 'getUser')
				->name('get');
			Route::put('settings', 'updateUserSetting')
				->name('update');
			Route::delete('/', 'deleteUser')
				->middleware('uneditable_demo')
				->name('delete');
		});

	/**
	 * vault routes
	 */
	Route::name('vault.')
		->prefix('user/vault')
		->controller(VaultController::class)
		->group(function () {
			Route::post('/', 'createUserVault')
				->middleware('uneditable_demo')
				->name('create');
			Route::put('{vault}', 'updateUserVaultName')
				->middleware('uneditable_demo')
				->name('update');
			Route::delete('/', 'deleteUserVault')
				->middleware('uneditable_demo')
				->name('delete');
		});

	/**
	 * notification gateway routes
	 */
	Route::name('notification_gateway.')
		->prefix('user/gateways')
		->controller(NotificationGatewayController::class)
		->group(function () {
			Route::get('/', 'getGateways')
				->name('get');
			Route::post('/', 'createGateway')
				->middleware('uneditable_demo')
				->name('create');
			Route::post('test', 'testGateway')
				->middleware('uneditable_demo')
				->name('test');
			Route::delete('/', 'deleteGateway')
				->middleware('uneditable_demo')
				->name('delete');
		});

	/**
	 * notification trigger routes
	 */
	Route::name('notification_trigger.')
		->prefix('user/notification')
		->controller(NotificationTriggerController::class)
		->group(function () {
			Route::get('/', 'getTrigger')
				->name('get');
			Route::post('/', 'createTrigger')
				->middleware('uneditable_demo')
				->name('create');
			Route::put('/', 'updateTrigger')
				->middleware('uneditable_demo')
				->name('update');
			Route::delete('/', 'deleteTrigger')
				->middleware('uneditable_demo')
				->name('delete');
		});

	/**
	 * payment routes
	 */
	Route::name('payment.')
		->prefix('user/payment')
		->controller(PaymentController::class)
		->group(function () {
			Route::get('state', 'getState')
				->middleware('uneditable_demo')
				->name('state');

			Route::get('transactions', 'getTransactions')
				->middleware('uneditable_demo')
				->name('transactions');

			Route::post('update_info', 'updateDetails')
				->middleware('uneditable_demo')
				->name('updateDetails');
		});
});
