<?php

namespace App\Enum;

enum FeeTypeName: string
{
    case PERCENTAGE_RATE = 'percentageRate';
    case FIXED_TIER = 'fixedTier';
    case FIXED_FEE = 'fixedFee';
}
