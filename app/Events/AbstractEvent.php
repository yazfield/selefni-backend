<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;

/**
 * Parent User events class.
 */
abstract class AbstractEvent
{
    use InteractsWithSockets, SerializesModels;

    /**
     * Parent Event channel name.
     *
     * @return string
     */
    abstract public function parentChannel(): string;

    /**
     * Channel name.
     *
     * @return string
     */
    abstract public function channel(): string;
}
