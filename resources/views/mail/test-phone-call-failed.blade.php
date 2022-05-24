@component('mail::message')
# {{ __('mail/testcall-failed.title') }}

{{ __('mail/testcall-failed.text') }}

@component('mail::button', ['url' => config('app.url') . '/#/phone-settings'])
{{ __('notifications/mail/general.button_title') }}
@endcomponent

{{ __('notifications/mail/general.regards') }}

{{ config('app.name') }}
@endcomponent
