@component('mail::message')
# {{ __('mail/low-account-balance.title') }}

{{ __('mail/low-account-balance.text', ['balance' => $balance, 'phoneCallAmount' => $phoneCallAmount]) }}

@component('mail::button', ['url' => config('app.url') . '/#/manage-phone-calls'])
{{ __('mail/low-account-balance.btn_text') }}
@endcomponent

{{ __('notifications/mail/general.regards') }}

{{ config('app.name') }}
@endcomponent
