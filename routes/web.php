<?php

use App\Http\Controllers\BotController;
use App\Http\Controllers\WebAppController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WebAppController::class, 'index']);

Route::match(['get', 'post'], 'telegram-bot', [BotController::class, 'handle']);
