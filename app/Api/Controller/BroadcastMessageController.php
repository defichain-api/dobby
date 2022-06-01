<?php

namespace App\Api\Controller;

use App\Http\Resources\BroadcastMessageCollection;
use App\Models\BroadcastMessage;

class BroadcastMessageController
{
	/**
	 * @throws \Exception
	 */
	public function currentMessages(): BroadcastMessageCollection
	{
		$messages = cache()->remember('broadcast_messages_current', now()->addMinutes(15), function(){
			return BroadcastMessage::whereDate('startTime', '<=', now())
				->whereDate('endTime', '>=', now())
				->orderByDesc('startTime')
				->get();
		});
		return BroadcastMessageCollection::make($messages);
	}

	/**
	 * @throws \Exception
	 */
	public function history(): BroadcastMessageCollection
	{
		$messages = cache()->remember('broadcast_messages_history', now()->addMinutes(15), function(){
			return BroadcastMessage::whereDate('endTime', '<', now())
				->orderByDesc('startTime')
				->limit(5)
				->get();
		});
		return BroadcastMessageCollection::make($messages);
	}
}
