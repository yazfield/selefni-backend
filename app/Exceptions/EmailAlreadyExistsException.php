<?php

namespace App\Exceptions;

class EmailAlreadyExistsException extends \Exception
{
    public function __construct()
    {
        $message = 'Email already exists';
        return parent::__construct($message);
    }
}