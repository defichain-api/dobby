<?php

return [
	'subject'            => 'WARNING: Your Vault may liquidate',
	'greeting'           => 'Hey Buddy!',
	'message'            => "***Urgent***: *DOBBY* realized, that your vault [:vault_id](:vault_deeplink) is at ***high risk*** with a ***ratio of :ratio %***. It may be liquidated soon, once the next oracle price becomes active.",
	'message_difference' => "***You should add :difference USD to restore a healthy vault with the ratio :ratio immediately!***",
];
