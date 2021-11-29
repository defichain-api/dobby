@component('mail::message')
# {{ __('notifications/mail/in_liquidation.greeting') }}
{{ __('notifications/mail/in_liquidation.message', [
	'vault_id'       => str_truncate_middle($vault->vaultId, 15, '...'),
    'vault_deeplink' => sprintf(config('links.vault_info_deeplink'), $vault->vaultId),
	'liquidation_block' => $vault->liquidationHeight,
]) }}

@component('mail::button', ['url' => sprintf(config('links.vault_info_deeplink'), $vault->vaultId)])
{{ __('notifications/mail/general.button_title') }}
@endcomponent

{{ __('notifications/mail/general.thank_you') }}

{{ __('notifications/mail/general.regards') }}<br>
{{ config('app.name') }}
@endcomponent
