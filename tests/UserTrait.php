<?php

namespace Tests;

use App\Services\Contracts\UserService;

trait UserTrait
{
    private $userService;

    public function setUp()
    {
        parent::setUp();
        $this->userService = app(UserService::class);
    }

    private function storeUserData()
    {
        return [
            'name' => 'mock',
            'email' => 'mock@email.com',
            'password' => 'mock',
            'phone_number' => '213666666666',
        ];
    }

    private function storeUser()
    {
        return $this->userService->store($this->storeUserData());
    }
}