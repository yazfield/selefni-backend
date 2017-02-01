<?php

use App\Services\Contracts\ItemService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Carbon\Carbon;

class ItemsControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        $this->itemService = app(ItemService::class);
    }

    private function storeItemData()
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
        $item = factory(App\Item::class)->make();
        $this->json('POST', 'api/items', $item)->seeJsonContains($item, 'password');
    }

    public function testShow()
    {
        $item = factory(App\Item::class)->make();
        $this->json('POST', 'api/items', $item);
        $this->json('GET', "api/items/{$item['id']}")->seeJsonContains($items);
    }

    public function testIndex()
    {
        $item = factory(App\Item::class)->make();
        $this->json('POST', 'api/items', $item);
        $this->json('GET', "api/items")->seeJsonContains([$items]);
    }

}