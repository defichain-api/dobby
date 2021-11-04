<?php

use App\Http\Controllers\WebAppController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WebAppController::class, 'index']);
