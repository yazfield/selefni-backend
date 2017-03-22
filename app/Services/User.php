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
use App\Exceptions\EmailAlreadyExistsException;
use App\Exceptions\ActivationCodeExpiredException;
use App\Exceptions\PhoneNumberAlreadyExistsException;
use App\Services\Contracts\UserService as UserServiceContract;

/**
 * User Service class.
 */
class User implements UserServiceContract
{
    use CommonServiceMethods;
    use InjectModel;

    public function __construct(UserModel $model)
    {
        $this->setModel($model);
    }

    public function createUserWithoutAccount(array $data) : UserModel
    {
        $user = new $this->model($data);
        $user->save();

        return $user;
    }

    public function store(array $data) : UserModel
    {
        // TODO: find a better way to get unique fields
        // TODO: many phone numbers
        $fields = ['email', 'phone_number'];
        // check if user already exists
        foreach ($fields as $field) {
            try {
                $user = $this->findBy($field, $data[$field], true);
                break;
            } catch (\Exception $e) {
                $user = null;
            }
        }
        if (! is_null($user) && ! $user->trashed()) {
            if ($user->email == $data['email']) {
                throw new EmailAlreadyExistsException();
            }
            if ($user->phone_number == $data['phone_number']) {
                throw new PhoneNumberAlreadyExistsException();
            }
        }
        if (! is_null($user) && $user->trashed()) {
            $user = $this->restoreUser($user, $data);
            event(new UserSubscribedEvent($user));

            return $user;
        }
        $user = $this->createUser($data);
        event(new UserSubscribedEvent($user));

        return $user;
    }

    private function createUser(array $data) : UserModel
    {
        // subscribe new user
        $user = new $this->model($data);
        $user
            ->requestActivation()
            ->save();

        return $user;
    }

    private function restoreUser(UserModel $user, array $data) : UserModel
    {
        // user had deleted his account
        $user->restore();
        $user->fill($data)
            ->requestActivation()
            ->save();

        return $user;
    }

    public function find($id, bool $includeTrashed = false) : UserModel
    {
        return $this->findBy(username_field($id), $id, $includeTrashed);
    }

    public function activate($id, int $code) : UserModel
    {
        $result = $id instanceof UserModel ? $id : $this->find($id);
        if (! $result->activate($code)) {
            throw new ActivationCodeExpiredException;
        }
        $result->save();

        return $result;
    }

    public function deactivate($id) : UserModel
    {
        throw new \Exception('TODO: Implement deactivate() method.');
    }

    public function update($id, array $data) : UserModel
    {
        $result = $id instanceof UserModel ? $id : $this->find($id);
        $result->fill($data)->save();

        return $result;
    }

    public function destroy($id) : bool
    {
        $user = $id instanceof UserModel ? $id : $this->find($id);
        $user->active = false;

        return $user->delete();
    }
}
