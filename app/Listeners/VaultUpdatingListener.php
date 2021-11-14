<?php

namespace App\Listeners;

use App\Events\VaultUpdatingEvent;

class VaultUpdatingListener
{
	public function handle(VaultUpdatingEvent $event)
	{
		// check ratio - send notifications
	}
}
