<?php

namespace App\Enums\Transaction;

enum Type: string
{
    case Manual = 'manual';

    case Bonus = 'bonus';

    case Reward = 'reward';
}
