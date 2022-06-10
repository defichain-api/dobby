@component('mail::message')
# {{ __('mail/no-credits-available.title') }}

{{ __('mail/no-credits-available.text') }}

@component('mail::button', ['url' => config('app.url') . '/#/manage-phone-calls'])
{{ __('mail/no-credits-available.btn_text') }}
@endcomponent

{{ __('notifications/mail/general.regards') }}

{{ config('app.name') }}
@endcomponent
