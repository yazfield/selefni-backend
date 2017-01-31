<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\UserEvents\UserSubscribedEvent' => [
            'App\Listeners\User\UserActivationEmail',
        ],
        'Illuminate\Notifications\Events\NotificationSending' => [
            'App\Listeners\NotificationSendingListener',
        ],
        'Illuminate\Notifications\Events\NotificationSent' => [
            'App\Listeners\NotificationSentListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
