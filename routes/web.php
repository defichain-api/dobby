<?php

use App\Http\Controllers\BotController;
use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WebController::class, 'index']);
Route::match(['get', 'post'], 'telegram-bot', [BotController::class, 'handle']);
