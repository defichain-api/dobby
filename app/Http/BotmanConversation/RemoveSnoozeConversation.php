<?php

namespace App\Http\BotmanConversation;

use App\Enum\NotificationGatewayType;
use App\Models\NotificationGateway;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Kurozora\Cooldown\Models\Cooldown;

class RemoveSnoozeConversation extends Conversation
{
	const VALUE_YES = 'yes';
	const VALUE_NO = 'no';

	public function run(): void
	{
		$user = NotificationGateway::where('type', NotificationGatewayType::TELEGRAM)
			->where('value', $this->bot->getUser()->getId())
			->first()?->user;

		if (is_null($user)) {
			$this->say(__('bot/setup.unknown_user', ['url' => config('app.url')]), ['parse_mode' => 'Markdown']);

			return;
		}

		$question = Question::create(__('bot/removeSnooze.question'))
			->addButtons([
				Button::create(__('bot/removeSnooze.buttons.yes'))->value(self::VALUE_YES),
				Button::create(__('bot/removeSnooze.buttons.no'))->value(self::VALUE_NO),
			]);

		$this->ask($question, function (Answer $answer) use ($user) {
			if (!$answer->isInteractiveMessageReply()) {
				$this->repeat();

				return;
			}
			if ($answer->getValue() === self::VALUE_NO) {
				$this->say(__('bot/removeSnooze.confirm_canceled'), ['parse_mode' => 'Markdown']);

				return;
			}
			$this->bot->typesAndWaits(3);

			foreach ($user->notificationTrigger() as $trigger) {
				Cooldown::where('model_type', 'App\Models\NotificationTrigger')
					->where('model_id', $trigger->id)
					->delete();
			}

			$this->say(__('bot/removeSnooze.unsnooze_confirm'), ['parse_mode' => 'Markdown']);
		}, ['parse_mode' => 'Markdown']);
	}
}
