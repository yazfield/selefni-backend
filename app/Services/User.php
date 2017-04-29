<?php
/**
 * Created by PhpStorm.
 * User: yzid
 * Date: 12/30/16
 * Time: 6:51 PM.
 */

namespace App\Services;

use App\Events\UserEvents\UserSubscribedEvent;
use App\Exceptions\ActivationCodeExpiredException;
use App\Exceptions\EmailAlreadyExistsException;
use App\Exceptions\PhoneNumberAlreadyExistsException;
use App\Item as ItemModel;
use App\Services\Contracts\UserService as UserServiceContract;
use App\User as UserModel;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;

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
        $user = null;
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

    private function restoreUser(UserModel $user, array $data) : UserModel
    {
        // user had deleted his account
        $user->restore();
        $user->fill($data)
            ->requestActivation()
            ->save();

        return $user;
    }

    private function createUser(array $data): UserModel
    {
        // subscribe new user
        $user = new $this->model($data);
        $user->requestActivation()->save();

        if (array_has($data, 'avatar')) {
            $user->setAvatar($data[ 'avatar' ]);
        }

        return $user;
    }

    public function setAvatar($id, UploadedFile $avatar): UserModel
    {
        $user = $this->find($id);
        $user->setAvatar($avatar);

        return $user;
    }

    // FIXME: this method is preparing a view of data, that should probably go to the controller
    public function getUserNotifications(UserModel $user)
    {
        $notifications = $user->notifications()
                    //->limit(config('selefni.user.notifications.limit', 5))
                    ->get()
                    ->toArray();

        $model = $this->model;
        return array_map(function ($notification) use ($model) {
            $user = $model->find($notification['data']['from_id'])->toArray();
            $notification['data']['from'] = array_only($user, ['id', 'avatar', 'name']);

            // FIXME: using model directly, needs repo
            $item = ItemModel::find($notification['data']['item_id'])->toArray();
            $notification['data']['item'] = array_only($item, ['id', 'name']);

            $notification['data']['item']['old_amount'] = $notification['data']['old_amount'];
            $notification['data']['item']['new_amount'] = $notification['data']['new_amount'];
            unset($notification['data']['old_amount']);
            unset($notification['data']['new_amount']);

            return $notification;
        }, $notifications);
    }

    public function setNotificationsRead(UserModel $user, array $ids)
    {
        return $user->unreadNotifications()
                             ->whereIn('id', $ids)
                             ->update([
                                 'read_at' => new Carbon
                             ]);
    }

    public function deleteNotifications(UserModel $user, array $ids)
    {
        return $user->notifications()
                    ->whereIn('id', $ids)
                    ->delete();
    }

    public function searchFriends(UserModel$user, string $term)
    {

        return $user->friends()
            ->where(function ($query) use($term) {
                $query->orWhere('name', 'like', sprintf('%%%s%%', $term));
                $query->orWhere('email', 'like', sprintf('%%%s%%', $term));
                $query->orWhere('phone_number', 'like', sprintf('%%%s%%', $term));
            })
            ->limit(3)
            ->get();
    }

    public function find($id, bool $includeTrashed = false): UserModel
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
