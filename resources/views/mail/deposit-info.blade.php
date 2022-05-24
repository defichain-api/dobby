@component('mail::message')
# {{ __('mail/deposit-info.title') }}

{{ __('mail/deposit-info.text', ['amount' => $amount, 'phoneCallAmount' => $phoneCallAmount, 'balance' => $balance]) }}

{{ __('notifications/mail/general.regards') }}

{{ config('app.name') }}
@endcomponent
