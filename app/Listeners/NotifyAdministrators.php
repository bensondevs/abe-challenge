<?php

namespace App\Listeners;

use App\Events\Reward\RewardRedeemedByCustomer;
use App\Models\User;
use Filament\Notifications\Notification;

class NotifyAdministrators
{
    public function handle(RewardRedeemedByCustomer $event): void
    {
        $content = $event->getNotificationMessage();

        User::query()->cursor()->each(
            fn (User $user) => $user->notifyNow(
                Notification::make()
                    ->title('New Reward Redeemed')
                    ->body($content)
                    ->toDatabase(),
            ),
        );
    }
}
