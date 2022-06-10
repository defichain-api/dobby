@component('mail::message')
# {{ __('mail/testcall-busy.title') }}

{{ __('mail/testcall-busy.text') }}


{{ __('notifications/mail/general.regards') }}

{{ config('app.name') }}
@endcomponent
