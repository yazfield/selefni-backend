<?php

namespace App\Listeners\User;

use App\Notifications\ActivationEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\UserEvents\UserSubscribedEvent;

class UserActivationEmail implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  UserSubscribedEvent $event
     *
     * @return void
     */
    public function handle(UserSubscribedEvent $event)
    {
        $event->getUser()->notify(new ActivationEmail);
    }
}
