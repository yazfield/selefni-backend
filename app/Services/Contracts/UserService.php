<?php
/**
 * Created by PhpStorm.
 * User: yzid
 * Date: 12/30/16
 * Time: 6:36 PM.
 */

namespace App\Services\Contracts;

/**
 * Interface UserService
 * All user related actions to be implemented by a UserService class.
 */
interface UserService
{
    /**
     * Find a user by a unique identifier.
     *
     * @param mixed $id             User identifier, should be unique.
     * @param bool  $includeTrashed Include trashed if set to true
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return \App\User user model if found.
     */
    public function find($id, $includeTrashed = false);

    /**
     * Find a user by adding search criteria: where $field = $value.
     * $field should be unique because the method returs one row.
     *
     * @param string $field          User table field name.
     * @param mixed  $value          $field value
     * @param bool   $includeTrashed Include trashed if set to true
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return \App\User user model if found.
     */
    public function findBy($field, $value, $includeTrashed = false);

    /**
     * Store a new user.
     *
     * @param array $data User data.
     *
     * @return \App\User stored user model.
     */
    public function store(array $data);

    /**
     * Activate user.
     *
     * @param mixed $id \App\User model or user identifier
     *
     * @return \App\User
     */
    public function activate($id);

    /**
     * Deactivate user.
     *
     * @param mixed $id \App\User model or user identifier
     *
     * @return \App\User
     */
    public function deactivate($id);

    /**
     * Update user.
     *
     * @param mixed $id \App\User model or user identifier
     * @param array $data
     *
     * @return \App\User
     */
    public function update($id, array $data);

    /**
     * Destroy user.
     *
     * @param mixed $id \App\User model or user identifier
     *
     * @return bool true if the user was deleted.
     */
    public function destroy($id);
}
