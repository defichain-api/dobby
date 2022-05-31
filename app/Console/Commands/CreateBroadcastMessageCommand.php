<?php

namespace App\Console\Commands;

use App\Models\BroadcastMessage;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CreateBroadcastMessageCommand extends Command
{
	protected $signature = 'broadcast:create';
	protected $description = 'Create broadcast message for UX';

	public function handle()
	{
		$headline = $this->ask('What\'s the title of the message?', '');
		$message = $this->ask('What\'s the message?', '');

		if ($headline == '' || $message == '') {
			$headline == '' && $this->error('title is required');
			$message == '' && $this->error('message is required');
			return;
		}

		$type = $this->choice('What\'s the type of the message?', ['default', 'info', 'warning', 'error']);
		$startDate = Carbon::parse($this->ask('Start date? (format: YYYY-MM-DD HH:mm)'));
		$endDate = Carbon::parse($this->ask('End date? (format: YYYY-MM-DD HH:mm)'));

		BroadcastMessage::create([
			'headline'  => $headline,
			'message'   => $message,
			'type'      => $type,
			'startTime' => $startDate,
			'endTime'   => $endDate,
		]);

		cache()->forget('broadcast_messages_current');

		$this->info('cleared broadcast message cache');
	}
}
