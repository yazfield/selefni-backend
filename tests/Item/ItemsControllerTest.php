<?php

namespace Tests\Item;

use Carbon\Carbon;
use Tests\TestCase;
use App\Services\Contracts\ItemService;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ItemsControllerTest extends TestCase
{
    use DatabaseMigrations;

    protected $itemService;

    public function setUp()
    {
        parent::setUp();
        $this->itemService = app(ItemService::class);
    }

    private function storeItemData()
    {
        // TODO: set other fields + borrowed to ..etc
        return [
            'name' => 'mock',
            'details' => 'mock det',
            'return_at' => (new Carbon)->toDateTimeString(),
            'type' => 'object',
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
        $item = factory(\App\Item::class)->make()->toArray();
        $this->json('POST', 'api/items', $item)->assertJson($item);
    }

    /*public function testShow()
    {
        $item = factory(App\Item::class)->make();
        $this->json('POST', 'api/items', $item);
        $this->json('GET', "api/items/{$item['id']}")->assertJson($items);
    }
    */
    public function testIndex()
    {
        $item = $this->storeItem()->toArray();
        $this->json('GET', 'api/items')->assertJson(['data' => [$item]]);
    }
}
