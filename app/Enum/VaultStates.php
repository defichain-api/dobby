<?php

namespace App\Enum;

class VaultStates
{
    const ACTIVE = 'active';
    const FROZEN = 'frozen';
    const INLIQUIDATION = 'inLiquidation';
    const MAYLIQUIDATE = 'mayLiquidate';
    const UNKNOWN = 'unknown';
    const ALL = [
        self::ACTIVE,
        self::FROZEN,
        self::INLIQUIDATION,
        self::MAYLIQUIDATE,
        self::UNKNOWN,
    ];
}
