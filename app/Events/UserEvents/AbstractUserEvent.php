<?php

namespace App\Events\UserEvents;

use App\Events\AbstractEvent;
use App\User as UserModel;
use Illuminate\Broadcasting\Channel;

/**
 * Parent User events class
 */
abstract class AbstractUserEvent extends AbstractEvent
{

    /**
     * UserModel model.
     * @var UserModel
     */
    protected $user;

    /**
     * Create a new event instance.
     * @return void
     */
    public function __construct(UserModel $user)
    {
        $this->user = $user;
    }

    /**
     * @return UserModel
     */
    public function getUser(): UserModel
    {
        return $this->user;
    }

    /**
     * @param UserModel $user
     */
    public function setUser(UserModel $user)
    {
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     * @return Channel|array
     */
    final public function broadcastOn()
    {
        return new Channel("{$this->parentChannel()}.{$this->channel()}");
    }

    final public function parentChannel(): string
    {
        return 'user';
    }

}
