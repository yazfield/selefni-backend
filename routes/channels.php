<?php
/**
 * Created by PhpStorm.
 * User: yzid
 * Date: 4/23/17
 * Time: 5:55 PM
 */

Broadcast::channel('user.{id}', function ($user, $id) {
    return $user->id == $id;
});