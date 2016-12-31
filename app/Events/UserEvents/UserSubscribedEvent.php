<?php

namespace App\Events\UserEvents;

class UserSubscribedEvent extends AbstractUserEvent
{
    public function channel(): string
    {
        return 'subscribed';
    }
}
