<?php

namespace App\Enums\Transaction;

use App\Enums\Concerns\EnumExtensions;

enum Type: string
{
    use EnumExtensions;

    case Manual = 'manual';

    case Bonus = 'bonus';

    case Reward = 'reward';
}
