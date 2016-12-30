<?php
/**
 * Created by PhpStorm.
 * User: yzid
 * Date: 12/30/16
 * Time: 6:51 PM
 */

namespace App\Services;


use App\Services\Contracts\UserService as UserServiceContract;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\User as UserModel;

/**
 * User Service class
 * @package App\Services
 */
class User implements UserServiceContract
{
    /**
     * @var UserModel
     */
    private $model;

    /**
     * @return UserModel
     */
    public function getModel()
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

    /**
     * User constructor.
     * @param UserModel $model
     */
    public function __construct(UserModel $model)
    {
        $this->setModel($model);
    }

    public function find($id, $includeTrashed = false)
    {
        $result = $this->model->where(username_field($id), $id);
        if($includeTrashed)
            $result = $result->withTrashed();
        $result = $result->first();
        return $this->returnOrThrow($result);
    }

    public function findBy($field, $value, $includeTrashed = false)
    {
        // TODO: Implement findBy() method.
    }

    public function store(array $data)
    {
        // TODO: Implement store() method.
    }

    public function activate($id)
    {
        // TODO: Implement activate() method.
    }

    public function deactivate($id)
    {
        // TODO: Implement deactivate() method.
    }

    public function update($id, array $data)
    {
        // TODO: Implement update() method.
    }

    public function destroy($id)
    {
        // TODO: Implement destroy() method.
    }

    /**
     * Throws exception if $result is null or returns $result.
     * @param UserModel|null $result
     * @return UserModel
     */
    private function returnOrThrow($result) {
        if(is_null($result))
            throw new ModelNotFoundException;
        return $result;
    }
}