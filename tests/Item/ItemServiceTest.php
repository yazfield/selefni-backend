<?php

namespace Tests\Item;

use App\Services\Contracts\ItemService;
use App\Services\Contracts\UserService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Carbon\Carbon;
use Tests\TestCase;

class ItemServiceTest extends TestCase
{
    use DatabaseMigrations;

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
        # TODO: set other fields + borrowed to ..etc
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
            'borrowed_from' => (string)$this->user->id,
            'amount' => null,
            'type' => 'object',
        ];
    }

    private function storeItem()
    {
        $this->item = $this->item ?? $this->itemService->store($this->storeItemData());
        return $this->item;
    }

    public function testStoreItemWithInexistingUser()
    {
        $itemData = $this->storeItemData();
        $itemData['borrowed_to'] = null;
        $itemData['new_user'] = 'borrowed_to';
        $itemData['borrowed_to_data'] = [
            'name' => 'mock2',
            'password' => 'mockmock',
            'phone_number' => '213666666662',
        ];
        $item = $this->itemService->store($itemData);
        $this->assertDatabaseHas('items', $item->toArray());
    }

    public function testStore()
    {
        $item = $this->storeItem();
        $this->assertTrue($item instanceof \App\Item);
        $data = $this->storeItemData();
        $this->assertDatabaseHas('items', $data);
    }

    public function testFind()
    {
        $item = $this->storeItem();
        $item2 = $this->itemService->find($item->id);
        $this->assertEquals($item->load('borrowedFrom', 'borrowedTo')->toArray(), $item2->toArray());
    }

    public function testPaginate()
    {
        $item = $this->storeItem()->load('borrowedFrom', 'borrowedTo');
        $pagination = $this->itemService->paginate()->toArray();
        $this->assertEquals($item->toArray(), $pagination['data'][0]);
    }

}
