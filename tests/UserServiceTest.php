<?php

namespace Tests;

use App\Services\Contracts\UserService;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserServiceTest extends TestCase
{
    use DatabaseMigrations;

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

    public function testFindBy()
    {
        $user = $this->storeUser();
        $user2 = $this->userService->findBy('phone_number', '213666666666');
        $user3 = $this->userService->findBy('email', 'mock@email.com');
        $user4 = $this->userService->findBy('id', $user->id);
        $this->assertEquals($user->toArray(), $user2->toArray());
        $this->assertEquals($user->toArray(), $user3->toArray());
        $this->assertEquals($user->toArray(), $user4->toArray());
    }

    public function testStoreUser()
    {
        $user = $this->storeUser();
        $this->assertTrue($user instanceof \App\User);
        $data = $this->storeUserData();
        $this->assertDatabaseHas('users', array_except($data, 'password'));
        $this->assertTrue(\Hash::check($data['password'], $user->password));
        $this->assertEquals(false, $user->active);
        $this->assertNotNull($user->activation_code);
    }

    public function testStoreDeletedUser()
    {
        $oldUser = $this->storeUser();
        $oldUser = $this->userService->activate($oldUser->id, $oldUser->activation_code);
        $oldUser->delete();
        $user = $this->storeUser();
        $this->assertEquals($oldUser->id, $user->id);
        $this->assertEquals(false, $user->active);
    }

    public function testUpdate()
    {
        $user = $this->storeUser();
        $data = [
            'name' => 'mocky',
            'email' => 'mocky@email.com',
            'password' => 'mocky',
            'phone_number' => '213666666669',
        ];
        $user2 = $this->userService->update($user, $data);
        $this->assertDatabaseHas('users', array_except($data, 'password'));
    }

    public function testActivate()
    {
        $user = $this->storeUser();
        $user2 = $this->userService->activate($user->id, $user->activation_code);
        $this->assertEquals(true, $user2->active);
    }

    public function testActivateFails()
    {
        $this->setExpectedException('App\Exceptions\ActivationCodeExpiredException');
        $user = $this->storeUser();
        $user2 = $this->userService->activate($user->id, 1);
        $this->assertEquals(true, $user2->active);
    }

    public function testStoreDeleted()
    {
        $user = $this->storeUser();
        $this->userService->destroy($user->id);
        $newuser = $this->storeUser();
        $this->assertEquals($user->id, $newuser->id);
    }
}
