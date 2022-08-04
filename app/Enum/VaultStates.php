<?php

namespace App\Enum;

class VaultStates
{
    const ACTIVE = 'active';
    const INACTIVE = 'inactive';
    const FROZEN = 'frozen';
    const INLIQUIDATION = 'in_liquidation';
    const MAYLIQUIDATE = 'may_liquidate';
    const UNKNOWN = 'unknown';
    const ALL = [
        self::ACTIVE,
        self::INACTIVE,
        self::FROZEN,
        self::INLIQUIDATION,
        self::MAYLIQUIDATE,
        self::UNKNOWN,
    ];
}
