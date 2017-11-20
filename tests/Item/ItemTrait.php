<?php
namespace Tests\Item;

use App\Services\Contracts\ItemService;
use App\Services\Contracts\UserService;
use Carbon\Carbon;


trait ItemTrait {
    private $itemService;
    private $item;
    private $user;

    public function setUp()
    {
        parent::setUp();
        $this->itemService = app(ItemService::class);
        $this->userService = app(UserService::class);
    }

    private function storeItemData()
    {
        // TODO: set other fields + borrowed to ..etc
        $this->user = $this->user ?? $this->userService->store([
            'name' => 'mock',
            'email' => 'mock@email.com',
            'password' => 'mockmock',
            'phone_number' => '213666666666',
        ]);

        return [
            'name' => 'mock',
            'details' => 'mock det',
            'return_at' => (new Carbon)->toDateTimeString(),
            'borrowed_to' => (string) $this->user->id,
            'borrowed_from' => (string) $this->user->id,
            'amount' => null,
            'type' => 'object',
        ];
    }

    private function storeItem()
    {
        $this->item = $this->item ?? $this->itemService->store($this->storeItemData());

        return $this->item;
    }
}