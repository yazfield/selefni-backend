<?php

use Illuminate\Database\Seeder;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        factory(App\Item::class, 2)->create([ 'borrowed_to' => 2, 'borrowed_from' => 3 ]);
        factory(App\Item::class, 'money', 2)->create([ 'borrowed_to' => 2, 'borrowed_from' => 3 ]);
        factory(App\Item::class, 'book', 2)->create([ 'borrowed_to' => 3, 'borrowed_from' => 2 ]);
    }
}
