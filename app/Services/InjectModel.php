<?php

namespace App\Services;

use \Illuminate\Database\Eloquent\Model;

trait InjectModel
{
    /**
     * @var Model
     */
    private $model;

    /**
     *
     */
    public function getModel() : Model
    {
        return $this->model;
    }

    /**
     *
     */
    public function setModel(Model $model)
    {
        $this->model = $model;
    }
}
