<?php

namespace App\Exceptions;

/**
 * Class PhoneNumberAlreadyExistsException
 * @package App\Exceptions
 */
class PhoneNumberAlreadyExistsException extends \Exception
{
    /**
     * PhoneNumberAlreadyExistsException constructor.
     */
    public function __construct()
    {
        $message = 'Phone number already exists';

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
                'phone_number' => 'The phone number elready exists',
            ],
        ], 400);
    }
}
