<?php

return [
	'snooze' => "[0,60]⏳ *Snooze activated*\r\n*DOBBY* won't send this notification again for the next :time hour.|[61,*]⏳ *Snooze activated*\r\n*DOBBY* won't send this notification again for the next :time hours.",

	'cooldown_times' => [
		'30'  => 'snooze 30 minutes',
		'60'  => 'snooze 1 hour',
		'180' => 'snooze 3 hours',
		'360' => 'snooze 6 hours',
	],
];
