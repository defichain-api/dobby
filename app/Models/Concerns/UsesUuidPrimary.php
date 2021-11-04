<?php

namespace App\Models\Concerns;

use Str;

/**
 * @codeCoverageIgnore
 */
trait UsesUuidPrimary
{
	protected static function bootUsesUuidPrimary()
	{
		static::creating(function ($model) {
			if (!$model->getKey()) {
				$model->{$model->getKeyName()} = (string)Str::uuid();
			}
		});
	}

	public function getIncrementing(): bool
	{
		return false;
	}

	public function getKeyType(): string
	{
		return 'string';
	}
}
