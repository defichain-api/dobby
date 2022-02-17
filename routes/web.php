<?php

use App\Http\Controllers\BotController;
use Illuminate\Support\Facades\Route;

Route::match(['get', 'post'], 'telegram-bot', [BotController::class, 'handle']);
