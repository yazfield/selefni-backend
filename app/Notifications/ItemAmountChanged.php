<?php

namespace App\Notifications;

use App\Item;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ItemAmountChanged extends Notification implements ShouldQueue
{
    use Queueable;

    protected $from;
    protected $item;
    protected $oldAmount;
    protected $newAmount;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $from, Item $item, int $oldAmount, int $newAmount)
    {
        $this->from = $from;
        $this->item = $item;
        $this->oldAmount = $oldAmount;
        $this->newAmount = $newAmount;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // TODO: check user settings for via
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'from_id' => $this->from->id,
            'item_id' => $this->item->id,
            'old_amount' => $this->oldAmount,
            'new_amount' => (int) $this->item->amount,
        ];
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'read_at' => null,
            'data' => [
                'from' => [
                    'id' => $this->from->id,
                    'name' => $this->from->name,
                    'avatar' => $this->from->avatar,
                ],
                'item' => [
                    'id' => $this->item->id,
                    'name' => $this->item->name,
                    'old_amount' => $this->oldAmount,
                    'new_amount' => (int) $this->newAmount,
                ],
            ],
        ];
    }
}
