@component('mail::message')
# {{ __('mail/deposit-info.title') }}

{{ __('mail/deposit-info.text_intro', ['amount' => $amount]) }}
{{ trans_choice('mail/deposit-info.text_calls', $phoneCallAmount, ['phoneCallAmount' => $phoneCallAmount, 'costs' => config('twilio.phone_call_cost')]) }}

{{ __('mail/deposit-info.text_final', ['balance' => $balance]) }}


{{ __('notifications/mail/general.regards') }}

{{ config('app.name') }}
@endcomponent
