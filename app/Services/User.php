<?php
/**
 * Created by PhpStorm.
 * User: yzid
 * Date: 12/30/16
 * Time: 6:51 PM.
 */

namespace App\Services;

use App\User as UserModel;
use App\Events\UserEvents\UserSubscribedEvent;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Contracts\UserService as UserServiceContract;

/**
 * User Service class.
 */
class User implements UserServiceContract
{
    /**
     * @var UserModel
     */
    private $model;

    /**
     * User constructor.
     *
     * @param UserModel $model
     */
    public function __construct(UserModel $model)
    {
        $this->setModel($model);
    }

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

    public function store(array $data)
    {
        // check if user already exists
        // TODO: find a better way to get unique fields
        $fields = ['email', 'phone_number'];
        $user = null;
        foreach ($fields as $field) {
            try {
                $user = $this->findBy($field, $data[$field], true);
            } catch (\Exception $e) {
                $user = null;
            }
        }
        if (! is_null($user)) {
            // user had deleted his account
            if ($user->trashed()) {
                $user->restore();
            } else {
                $user->fill($data)->save();
            }

            // event: send activation email
            return $user;
        } else {
            // subscribe new user
            $data['active'] = false;
            $result = $this->model->create($data);
            event(new UserSubscribedEvent($result));

            return $result;
        }
    }

    public function findBy($field, $value, $includeTrashed = false)
    {
        $result = $this->model->where($field, $value);
        if ($includeTrashed) {
            $result = $result->withTrashed();
        }

        return $this->returnOrThrow($result->first());
    }

    /**
     * Throws exception if $result is null or returns $result.
     *
     * @param UserModel|null $result
     *
     * @return UserModel
     */
    private function returnOrThrow($result)
    {
        if (is_null($result)) {
            throw new ModelNotFoundException;
        }

        return $result;
    }

    public function activate($id)
    {
        if ($id instanceof UserModel) {
            $result = $id;
        } else {
            $result = $this->find($id);
        }
        $result->active = true;
        $result->save();

        return $result;
    }

    public function find($id, $includeTrashed = false)
    {
        $result = $this->model->where(username_field($id), $id);
        if ($includeTrashed) {
            $result = $result->withTrashed();
        }
        $result = $result->first();

        return $this->returnOrThrow($result);
    }

    public function deactivate($id)
    {
        throw new \Exception('TODO: Implement deactivate() method.');
    }

    public function update($id, array $data)
    {
        if ($id instanceof UserModel) {
            $result = $id;
        } else {
            $result = $this->find($id);
        }
        $result->fill($data)->save();

        return $result;
    }

    public function destroy($id)
    {
        $user = $this->find($id);
        $user->active = false;

        return $user->delete();
    }
}
