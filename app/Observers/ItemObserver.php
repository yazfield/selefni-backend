<?php
/**
 * Created by PhpStorm.
 * User: yzid
 * Date: 4/23/17
 * Time: 6:59 PM
 */

namespace App\Observers;
use App\Item;
use App\Notifications\ItemAmountChanged;
use App\User;

class ItemObserver
{
    // FIXME: shouldn't be updated event?
    public function updating(Item $item)
    {
        // FIXME: is it better to notify from ItemService?
        $user = auth()->user();

        if($item->isDirty('amount')) {
            $friendField = $item->borrowed_from == $user->id ? 'borrowedTo' : 'borrowedFrom';
            $item->load($friendField);
            $friend = $item->{$friendField};
            $this->maybeNotify($friend, $user, $item);
        }
    }

    private function maybeNotify(User $user, User $from, Item $item)
    {
        if($user->active) {
            $oldAmount = (int) $item->getOriginal('amount');
            $newAmount = (int) $item->amount;
            $user->notify(new ItemAmountChanged($from, $item, $oldAmount, $newAmount));
        }
    }
}