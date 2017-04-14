<?php

namespace App\Services;

use App\Item as ItemModel;
use App\Services\Contracts\ItemService as ItemServiceContract;
use App\Services\Contracts\UserService as UserServiceContract;
use App\User as UserModel;

/**
 * Item Service class.
 */
class Item implements ItemServiceContract
{
    use CommonServiceMethods;
    use InjectModel;

    /**
     * Item constructor.
     *
     * @param ItemModel $model
     */
    public function __construct(ItemModel $model)
    {
        $this->setModel($model);
    }

    public function find($id, bool $includeTrashed = false) : ItemModel
    {
        $q = $this->model->with('borrowedTo', 'borrowedFrom');
        if ($includeTrashed) {
            $q = $q->withTrashed();
        }
        return $q->findOrFail($id);
    }

    // TODO: put perPage in a config file
    public function paginate(int $perPage = 6)
    {
        return $this->model->with('borrowedTo', 'borrowedFrom')->paginate($perPage);
    }

    public function paginateUserItems(UserModel $user, int $perPage = 6)
    {
        $id = $user->id;

        return $this->model->where(function ($query) use ($id) {
                $query->where('borrowed_to', $id)->orWhere('borrowed_from', $id);
            })->with('borrowedTo', 'borrowedFrom')->paginate($perPage);
    }

    /**
     * data['new_user'] = 'borrowed_from' or 'borrowed_to'
     * data['borrowed_from_data'] = user data
     * TODO: find a way to avoid this coupling.
     */
    public function store(array $data) : ItemModel
    {
        $userService = app(UserServiceContract::class);
        if (isset($data['new_user'])) {
            $userData = $data[$data['new_user'].'_data'];
            $user = $userService->createUserWithoutAccount($userData);
            $data[$data['new_user']] = $user->id;
        }

        return $this->model->create($data);
    }

    public function update($id, array $data) : ItemModel
    {
        $item = $this->model->findOrFail($id);

        $userService = app(UserServiceContract::class);
        if (isset($data[ 'new_user' ])) {
            $userData = $data[ $data[ 'new_user' ].'_data' ];
            $user = $userService->createUserWithoutAccount($userData);
            $data[ $data[ 'new_user' ] ] = $user->id;
        }

        dd($data);

        $item->fill($data)
            ->save();

        return $item;
    }

    public function destroy($id) : bool
    {
        $item = $this->model->findOrFail($id);

        return $item->delete();
    }
}
