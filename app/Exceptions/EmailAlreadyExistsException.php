<?php

namespace App\Exceptions;

/**
 * Class EmailAlreadyExistsException
 * @package App\Exceptions
 */
class EmailAlreadyExistsException extends \Exception
{
    /**
     * EmailAlreadyExistsException constructor.
     */
    public function __construct()
    {
        $message = 'Email already exists';

        return parent::__construct($message);
    }

    /**
     * Renders a response
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request)
    {
        return response()->json([
            'errors' => [
                // FIXME: lang file
                'email' => 'The email elready exists',
            ],
        ], 400);
    }
}
