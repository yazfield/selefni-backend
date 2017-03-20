<?php

namespace Tests\Item;

use App\Services\Contracts\ItemService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Carbon\Carbon;
use Tests\TestCase;

class ItemServiceTest extends TestCase
{
    use DatabaseMigrations;

    private $itemService;

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
            'borrowed_to' => '1',
            'borrowed_from' => '2',
        ];
    }

    private function storeItem()
    {
        return $this->itemService->store($this->storeItemData());
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
        $this->assertEquals($item->toArray(), $item->toArray());
    }

    public function testPaginate()
    {
        $item = $this->storeItem();
        $pagination = $this->itemService->paginate()->toArray();
        $this->assertEquals($item->toArray(), array_except($pagination['data'][0], ['amount', 'type']));
    }

}
