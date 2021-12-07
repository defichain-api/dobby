@component('mail::message')
# {{ __('notifications/mail/warning.greeting') }}
{{ __('notifications/mail/warning.message', [
	'ratio' => $notificationTrigger->ratio,
]) }}

@component('mail::table')
    | {{ __('notifications/mail/general.table.vault_id') }} | {{ __('notifications/mail/general.table.current_ratio') }} | {{ __('notifications/mail/general.table.collateral_value') }} | {{ __('notifications/mail/general.table.loan_value') }} |
    | ------------- |:-------------:| :----------------: | :--------: |
    | {{ str_truncate_middle($vault->vaultId, 15) }} | {{ $vault->collateralRatio }} % |{{ number_format_for_language($vault->collateralValue) }} USD | {{ number_format_for_language($vault->loanValue) }} USD |
@endcomponent

{{ __('notifications/mail/warning.message_difference', [
	'ratio' => $notificationTrigger->ratio,
	'difference' => app(\App\Models\Service\VaultService::class)->calculateCollateralDifference(
		$vault,
		$notificationTrigger->ratio
	),
]) }}

@component('mail::button', ['url' => sprintf(config('links.vault_info_deeplink'), $vault->vaultId)])
{{ __('notifications/mail/general.button_title') }}
@endcomponent

{{ __('notifications/mail/general.thank_you') }}

{{ __('notifications/mail/general.regards') }}<br>
{{ config('app.name') }}
@endcomponent
