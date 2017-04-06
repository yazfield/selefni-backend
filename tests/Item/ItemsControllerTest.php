<?php

namespace Tests\Item;

use Carbon\Carbon;
use Tests\TestCase;
use App\Services\Contracts\ItemService;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ItemsControllerTest extends TestCase
{
    use DatabaseMigrations;
    use ItemTrait;

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
        $item = $this->storeItem()->load('borrowedFrom', 'borrowedTo');
        $item = $item->toArray();
        $this->json('GET', 'api/items')->assertJson(['data' => [$item]]);
    }
}
