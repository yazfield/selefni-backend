<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Services\Contracts\UserService;

class UserServiceTest extends TestCase
{
    use DatabaseMigrations;

    private $userService;

    public function setUp() {
        parent::setUp();
        $this->userService = app(UserService::class);
    }

    private function storeUserData() {
        return [
            'name' => 'mock',
            'email' => 'mock@email.com',
            'password' => 'mock',
            'phone_number' => '213666666666',
        ];
    }

    private function storeUser() {
        return $this->userService->store($this->storeUserData());
    }

    public function testFind()
    {
        $user = $this->storeUser();
        $user2 = $this->userService->find('213666666666');
        $user3 = $this->userService->find('mock@email.com');
        $user4 = $this->userService->find($user->id);
        $this->assertEquals($user->toArray(), $user2->toArray());
        $this->assertEquals($user->toArray(), $user3->toArray());
        $this->assertEquals($user->toArray(), $user4->toArray());
    }
}
