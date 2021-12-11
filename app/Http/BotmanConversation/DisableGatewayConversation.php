<?php

namespace App\Http\BotmanConversation;

use App\Api\Service\NotificationGatewayService;
use App\Enum\NotificationGatewayType;
use App\Models\NotificationGateway;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class DisableGatewayConversation extends Conversation
{
	const VALUE_YES = 'yes';
	const VALUE_NO = 'no';

	public function __construct(protected NotificationGatewayService $gatewayService)
	{
	}

	public function run(): void
	{
		$gateway = NotificationGateway::where('type', NotificationGatewayType::TELEGRAM)
			->where('value', $this->bot->getUser()->getId())
			->first();
		$user = $gateway?->user;

		$question = Question::create(__('bot/disableGateway.question'))
			->addButtons([
				Button::create(__('bot/disableGateway.buttons.yes'))->value(self::VALUE_YES),
				Button::create(__('bot/disableGateway.buttons.no'))->value(self::VALUE_NO),
			]);
		$this->ask($question, function (Answer $answer) use ($user, $gateway) {
			if (!$answer->isInteractiveMessageReply()) {
				$this->repeat();

				return;
			}
			if ($answer->getValue() === self::VALUE_NO) {
				$this->say(__('bot/disableGateway.confirm_canceled'), ['parse_mode' => 'Markdown']);

				return;
			}
			$this->say(__('bot/disableGateway.deleting.step1'));
			$this->bot->typesAndWaits(3);
			$this->say(__('bot/disableGateway.deleting.step2'));
			$this->bot->typesAndWaits(5);

			if (!$this->gatewayService->delete($user, $gateway->id)) {
				$this->say(__('bot/disableGateway.deleting.error'), ['parse_mode' => 'Markdown']);

				return;
			}
			$this->say(__('bot/disableGateway.deleting.confirm'), ['parse_mode' => 'Markdown']);
		}, ['parse_mode' => 'Markdown']);
	}
}
