<?php

namespace App\Services;
use App\User as UserModel;

trait InjectModel {
    /**
     * @var Model
     */
    private $model;

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param Model $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }
}