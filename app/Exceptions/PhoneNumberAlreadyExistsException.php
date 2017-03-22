<?php

namespace App\Exceptions;

class PhoneNumberAlreadyExistsException extends \Exception
{
    public function __construct()
    {
        $message = 'Phone number already exists';

        return parent::__construct($message);
    }
}
