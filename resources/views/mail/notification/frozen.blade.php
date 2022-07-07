@component('mail::message')
# {{ __('notifications/mail/frozen.greeting') }}
{{ __('notifications/mail/frozen.message', [
	'vault_id'          => str_truncate_middle($vault->vaultId, 15, '...'),
	'vault_name'        => $vaultName ?? '',
    'vault_deeplink'    => $vault->deeplink(),
    'channel_url'       => config('links.defichain_announcement_channel'),
]) }}

@component('mail::button', ['url' => config('links.dobby_dashboard')])
{{ __('notifications/mail/general.button_title') }}
@endcomponent

{{ __('notifications/mail/general.thank_you') }}

{{ __('notifications/mail/general.regards') }}<br>
{{ config('app.name') }}
@endcomponent
