<?php

namespace Tests\Item;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ItemServiceTest extends TestCase
{
    use DatabaseMigrations;
    use ItemTrait;

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
