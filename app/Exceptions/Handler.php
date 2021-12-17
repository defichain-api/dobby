<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use NotificationChannels\Telegram\Exceptions\CouldNotSendNotification;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        CouldNotSendNotification::class,
    ];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
