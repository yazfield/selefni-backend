<?php

namespace App\Services\Contracts;

use App\Item;
use App\Services\Contracts\UserService;

/**
 * Interface ItemService
 * All item related actions to be implemented by a ItemService class.
 */
interface ItemService
{
    /**
     * Find an item by a unique identifier.
     *
     * @param mixed $id             Item identifier, should be unique.
     * @param bool  $includeTrashed Include trashed if set to true
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return Item item model if found.
     */
    public function find($id, bool $includeTrashed = false) : Item;

    /**
     * Find a user by adding search criteria: where $field = $value.
     * $field should be unique because the method returs one row.
     *
     * @param string $field          Item table field name.
     * @param mixed  $value          $field value
     * @param bool   $includeTrashed Include trashed if set to true
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return Item user model if found.
     */
    public function findBy(string $field, $value, bool $includeTrashed = false);

    /**
     * Store a new item.
     *
     * @param array $data item data.
     *
     * @return Item stored item model.
     */
    public function store(array $data) : Item;

    /**
     * Update item.
     *
     * @param mixed $id Item model or item identifier
     * @param array $data
     *
     * @return Item
     */
    public function update($id, array $data) : Item;

    /**
     * Destroy item.
     *
     * @param mixed $id Item model or item identifier
     *
     * @return bool true if the item was deleted.
     */
    public function destroy($id) : bool;

    /**
     *
     */
    public function paginate(int $perPage=15);

}