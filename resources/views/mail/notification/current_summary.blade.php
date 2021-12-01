@component('mail::message')
# {{ __('notifications/mail/current_summary.greeting') }}
{{ trans_choice('notifications/mail/current_summary.message', count($vaults)) }}

@component('mail::table')
    | {{ __('notifications/mail/general.table.vault_id') }} | {{ __('notifications/mail/general.table.min_ratio') }}|{{ __('notifications/mail/general.table.current_ratio') }} | {{ __('notifications/mail/general.table.collateral_value') }} | {{ __('notifications/mail/general.table.loan_value') }} |
    | ------------- |:-------------:|:-------------:| :----------------: | :--------: |
    @foreach($vaults as $vault)
    | [{{ str_truncate_middle($vault['vault_id'], 15) }}]({{ $vault['vault_deeplink'] }}) | {{ $vault['min_col_ratio'] }} % | {{$vault['current_ratio'] }} % |{{ round($vault['collateral_amount'], 2) }} USD | {{ round($vault['loan_value'], 2) }} USD |
    @endforeach
@endcomponent

@component('mail::button', ['url' => config('app.url')])
{{ __('notifications/mail/general.button_title') }}
@endcomponent

{{ __('notifications/mail/general.thank_you') }}

{{ __('notifications/mail/general.regards') }}<br>
{{ config('app.name') }}
@endcomponent
