<?php
/**
 * Created by PhpStorm.
 * User: yzid
 * Date: 4/23/17
 * Time: 5:03 PM
 */

namespace App\Events\ItemEvents;

use App\Item;
use App\User;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ItemAmountUpdated implements ShouldBroadcast
{
    protected $fromUser;
    protected $toUser;
    protected $item;
    protected $oldAmount;

    public function __construct(User $fromUser, User $toUser, Item $item, int $oldAmount)
    {
        $this->fromUser = $fromUser;
        $this->toUser = $toUser;
        $this->item = $item;
        $this->oldAmount = $oldAmount;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('App.User.' . $this->toUser->id);
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'notification\\item\\amount-updated';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'item' => [
                'id' => $this->item->id,
                'name' => $this->item->name,
                'old_amount' => $this->oldAmount,
                'new_amount' => $this->item->amount,
            ],
            'user' => [
                'id' => $this->fromUser->id,
                'name' => $this->fromUser->name,
            ]
        ];
    }

}