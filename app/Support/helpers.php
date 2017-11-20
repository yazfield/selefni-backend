<?php
/**
 * Created by PhpStorm.
 * User: yzid
 * Date: 12/30/16
 * Time: 6:58 PM.
 */
if (! function_exists('username_field')) {
    /**
     * Decides what field to look in for finding a user.
     *
     * @param mixed $username user unique identifier
     *
     * @return string users table field
     */
    function username_field($username)
    {
        if (preg_match('/^\(?\+?([0-9]{1,4})\)?[-\. ]?(\d{3})[-\. ]?([0-9]{7})$/', $username)) {
            return 'phone_number';
        } else {
            if (is_numeric($username)) {
                return 'id';
            } else {
                if (strpos($username, '@')) {
                    return 'email';
                } else {
                    return 'id';
                }
            }
        }
    }
}
