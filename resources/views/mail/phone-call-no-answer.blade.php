@component('mail::message')
    # {{ __('mail/call-no_answer.title') }}

    {{ __('mail/call-no_answer.text') }}


    {{ __('notifications/mail/general.regards') }}

    {{ config('app.name') }}
@endcomponent
