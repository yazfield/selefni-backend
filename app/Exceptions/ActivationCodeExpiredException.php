<?php

namespace App\Exceptions;

class ActivationCodeExpiredException extends \Exception
{
    public function __construct()
    {
        $message = 'User Activation Code has expired';

        return parent::__construct($message);
    }
}
