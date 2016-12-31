<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;

/**
 * Parent User events class
 */
abstract class AbstractEvent
{
    use InteractsWithSockets, SerializesModels;

    /**
     * Parent Event channel name.
     * @return string
     */
    abstract public function parentChannel(): string;

    /**
     * Channel name.
     * @return string
     */
    abstract public function channel(): string;
}
