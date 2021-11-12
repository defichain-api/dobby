<?php

return [
	'setup_wizard' => [
		'intro'  => [
			'headline'       => 'Hello, friend',
			'description'    => "This is Dobby - your personal DeFiChain house elf. Dobby is happy because you found your way to him. He is very useful because he keeps you informed about your DeFiChain loans. So, dobby enables all his friends to keep up with the changes on their loans by keeping them notified when one about to get in trouble.",
			'read_more_text' => "This Service will send you messages when some of your vaults on DeFiChain need attention. For example when they're close to being liquidated or if you just want to keep track of what's going on with your loans and how healty your vaults are. 
This is achieved by a combination of this app and - what we call - notification channels. 
You can choose from receiving messages via Telegram and Email for now. More will follow.",
			'buttons'        => [
				'or_text'   => 'OR',
				'read_more' => 'Read more...',
				'show_less' => 'Show less',
				'start'     => 'Start Setup (it\'s easy)',
				'demo'      => 'just show me a demo',
			],
		],
		'step_1' => [
			'title'   => 'Setup Dobby',
			'tabs'    => [
				'tab_1' => [
					'title'           => "Okay, let's get started",
					'sub_title'       => 'What to do',
					'sub_title_small' => '',
					'text'            => 'We will ask you for your vaults and automatically create a pseudo-anonymous account for you. This account is neccessary for connecting your vaults with your notification channels.',
				],
				'tab_2' => [
					'title'           => "You'll be able to name your vaults later.",
					'sub_title'       => 'Your Vaults',
					'sub_title_small' => 'at least one',
					'text'            => "Fill in your DeFiChain addresses holding vaults or your vault ids directly. Please fill in at least one. If you don't have a vault yet, you can take a look at the demo.",
				],
				'tab_3' => [
					'title'           => "You can copy your key to the clipboard.",
					'sub_title'       => 'Your Account Key',
					'sub_title_small' => "Don't lose it!",
					'text'            => "Please write it down and keep it in a safe place.",
				],
				'tab_4' => [
					'title'           => "You can copy your key to the clipboard.",
					'sub_title'       => 'Finished',
					'sub_title_small' => "",
					'text'            => "Try out different ad text to see what brings in the most customers, and learn how to enhance your ads using features like ad extensions. If you run into any problems with your ads, find out how to tell if they're running and how to resolve approval issues.",
				],
			],
			'buttons' => [
				'back'     => 'back',
				'continue' => 'continue',
				'finish'   => 'finish',
			],
		],
	],
];
