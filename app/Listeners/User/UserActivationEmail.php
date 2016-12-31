<?php

namespace App\Listeners\User;

use App\Events\UserEvents\UserSubscribedEvent;
use App\Notifications\ActivationEmail;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserActivationEmail implements ShouldQueue
{
    /**
     * Handle the event.
     * @param  UserSubscribedEvent $event
     * @return void
     */
    public function handle(UserSubscribedEvent $event)
    {
        $event->getUser()->notify(new ActivationEmail);
    }
}
