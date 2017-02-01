<?php

namespace App\Services;

use App\Item as ItemModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Contracts\ItemService as ItemServiceContract;
use Carbon\Carbon;

/**
 * Item Service class.
 */
class Item implements ItemServiceContract
{
    use CommonServiceMethods;

    /**
     * @var ItemModel
     */
    private $model;

    /**
     * Item constructor.
     *
     * @param ItemModel $model
     */
    public function __construct(ItemModel $model)
    {
        $this->setModel($model);
    }

    /**
     * @return ItemModel
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param ItemModel $model
     */
    public function setModel(ItemModel $model)
    {
        $this->model = $model;
    }

    public function find($id, $includeTrashed = false)
    {

    }

    public function findBy($field, $value, $includeTrashed = false)
    {

    }

    public function paginate(int $perPage=15)
    {
        return $this->model->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {

    }

    public function destroy($id)
    {

    }
}