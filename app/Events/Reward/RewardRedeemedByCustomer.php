<?php

namespace App\Events\Reward;

use App\Models\Customer;
use App\Models\Reward;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RewardRedeemedByCustomer
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        protected Customer $customer,
        protected Reward $reward,
    ) {}

    public function getNotificationMessage(): string
    {
        return $this->customer->name . ' has redeemed ' . $this->reward->title;
    }
}
