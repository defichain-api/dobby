@component('mail::message')
# {{ __('mail/call-failed.title') }}

{{ __('mail/call-failed.text') }}

@component('mail::table')
    | Vault         | Current Ratio | Next Ratio| State |
    | ------------- |:-------------:| :--------: | :--------: |
    @foreach($dobbyUser->vaults as $vault)
        @if($vault->state && $vault->collateralRatio > 0)
            | {{ $vault->pivot->name ?? str_truncate_middle($vault->vaultId, 15) }} | {{ $vault->collateralRatio }} % | {{ $vault->nextCollateralRatio }} % | {{ $vault->state }} |
        @endif
    @endforeach
@endcomponent

{{ __('notifications/mail/general.regards') }}

{{ config('app.name') }}
@endcomponent
