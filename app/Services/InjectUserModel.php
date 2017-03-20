<?php

namespace App\Services;
use App\User as UserModel;

trait InjectUserModel {
    /**
     * @var UserModel
     */
    private $model;

    /**
     * @return UserModel
     */
    public function getModel() : UserModel
    {
        return $this->model;
    }

    /**
     * @param UserModel $model
     */
    public function setModel(UserModel $model)
    {
        $this->model = $model;
    }
}