# Webhooks

There are different types of webhooks (json format). All types have the attributes
`type`, `message` and `data`. The content of the `data` differs.

More details below:

### Info / warning notifications

```
{
    'type'    => 'next_ratio',
    'message' => 'vaults next ratio triggered this info notification',
    'data'    => [
        'vaultId'                         => string,
        'vaultName'                       => string,
        'vaultDeeplink'                   => string, // link to defiscan
        'ratioTriggered'                  => int,
        'currentRatio'                    => int,
        'nextRatio'                       => int,
        'ratioActiveInMin'                => int, // #min to next tick
        'ratioActiveInBlocks'             => int, // #blocks to next tick
        'loanSchemeMinCollaterationRatio' => int,
        'collateralAmount'                => float,
        'loanValue'                       => float,
        'difference'                      => int // difference between trigger ratio and current ratio
    ]
}
```

### Vault state webhook

```
{
    'type'    => 'active', // could be: active, inLiquidation, mayLiquidate, frozen, liquidated
    'message' => 'vault switched back to state ' . 'active',
    'data'    => [
        'vaultId'       => string,
        'vaultName'     => string,
        'stateBefore'   => string,
        'vaultDeeplink' => string // link to defiscan
    ]
}
```
