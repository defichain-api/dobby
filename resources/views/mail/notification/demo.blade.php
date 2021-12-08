@component('mail::message')
# {{ __('notifications/mail/demo.greeting') }}
{{ __('notifications/mail/demo.message') }}

@component('mail::button', ['url' => $url])
{{ __('notifications/mail/general.button_notifications') }}
@endcomponent

{{ __('notifications/mail/general.thank_you') }}

{{ __('notifications/mail/general.regards') }}<br>
{{ config('app.name') }}
@endcomponent
