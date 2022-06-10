@component('mail::message')
# {{ __('mail/testcall-no_answer.title') }}

{{ __('mail/testcall-no_answer.text') }}


{{ __('notifications/mail/general.regards') }}

{{ config('app.name') }}
@endcomponent
