@component('mail::message')
# {{ __('notifications/mail/frozen.greeting') }}
{{ __('notifications/mail/frozen.message', [
	'vault_id'          => str_truncate_middle($vault->vaultId, 15, '...'),
	'vault_name'        => $vaultName ?? '',
    'vault_deeplink'    => sprintf(config('links.vault_info_deeplink'), $vault->vaultId),
    'channel_url'       => config('links.defichain_announcement_channel'),
]) }}

@component('mail::button', ['url' => sprintf(config('links.vault_info_deeplink'), $vault->vaultId)])
{{ __('notifications/mail/general.button_title') }}
@endcomponent

{{ __('notifications/mail/general.thank_you') }}

{{ __('notifications/mail/general.regards') }}<br>
{{ config('app.name') }}
@endcomponent
