<?php

use App\Services\Contracts\ItemService;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ItemServiceTest extends TestCase
{
    use DatabaseMigrations;

    private $itemService;

    public function setUp()
    {
        parent::setUp();
        $this->itemService = app(ItemService::class);
    }

    private function storeUserData()
    {
        # TODO: set other fields + borrowed to ..etc
        return [
            'name' => 'mock',
            'details' => 'mock det',
            'return_at' => (new Carbon)->toDateTimeString(),
            'borrowed_to' => 1,
            'borrowed_from' => 2,
        ];
    }

    private function storeItem()
    {
        return $this->itemService->store($this->storeItemData());
    }

    public function testStore()
    {
        $item = $this->storeItem();
        $this->assertTrue($item instanceof App\Item);
        $data = $this->storeItemData();
        $this->seeInDatabase('items', $data);
    }

    public function testFind()
    {
        $item = $this->storeItem();
        $item2 = $this->itemService->find($item->id);
        $this->assertEquals($item->toArray(), $item->toArray());
    }

    public function testPaginate()
    {
        $user = $this->storeUser();
        $user2 = $this->userService->findBy('phone_number', '213666666666');
        $user3 = $this->userService->findBy('email', 'mock@email.com');
        $user4 = $this->userService->findBy('id', $user->id);
        $this->assertEquals($user->toArray(), $user2->toArray());
        $this->assertEquals($user->toArray(), $user3->toArray());
        $this->assertEquals($user->toArray(), $user4->toArray());
    }

}
