<?php

use App\Api\Controller\SetupController;
use App\Api\Controller\UserController;
use App\Api\Controller\VaultController;
use Illuminate\Support\Facades\Route;

Route::post('setup', [SetupController::class, 'setup'])
	->name('setup');

Route::middleware(['webapp_auth'])->group(function () {
	Route::get('user', [UserController::class, 'getUser'])
		->name('user.get');

	Route::post('user/vault', [VaultController::class, 'createUserVault'])
		->name('vault.create');
});
