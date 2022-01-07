@component('mail::message')
# {{ __('notifications/mail/next_ratio.greeting') }}
{{ __('notifications/mail/next_ratio.message', [
	'vault_id'       => str_truncate_middle($vault->vaultId, 15, '...'),
	'vault_name'     => $vaultName ?? '',
	'vault_deeplink' => sprintf(config('links.vault_info_deeplink'), $vault->vaultId),
	'next_ratio'     => $vault->nextCollateralRatio,
	'block_diff'     => $ratioRepository->diffToNextTick(),
	'diff_min'       => $ratioRepository->minutesToNextTick(),
	'trigger_ratio'  => $notificationTrigger->ratio,
]) }}

@component('mail::button', ['url' => sprintf(config('links.vault_info_deeplink'), $vault->vaultId)])
{{ __('notifications/mail/general.button_title') }}
@endcomponent

{{ __('notifications/mail/general.thank_you') }}

{{ __('notifications/mail/general.regards') }}<br>
{{ config('app.name') }}
@endcomponent
