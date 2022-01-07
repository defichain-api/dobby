<?php

namespace App\Events;

use App\Models\Vault;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VaultUpdatingNextRatioEvent
{
	use Dispatchable, SerializesModels;

	public function __construct(protected Vault $vault)
	{
	}

	public function vault(): Vault
	{
		return $this->vault;
	}
}
