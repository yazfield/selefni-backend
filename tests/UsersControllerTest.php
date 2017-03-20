<?php

namespace Tests;

use App\Services\Contracts\UserService;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UsersControllerTest extends TestCase
{
    use DatabaseMigrations;
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
            'password' => 'mockmock',
            'phone_number' => '213666666666',
        ];
    }

    private function storeUser()
    {
        return $this->userService->store($this->storeUserData());
    }
    public function testStore()
    {
        $user = factory(\App\User::class)->make();
        $data = array_add($user->toArray(), 'password', 'mysecret');
        $this->json('POST', 'api/users', $data)->assertJson(array_except($data, 'password'));
    }

    public function testStoreDeleted()
    {
        $user = $this->storeUser();
        $user->delete();
        $result = $this->json('POST', 'api/users', $this->storeUserData());
        $result->assertJson(array_except($this->storeUserData(), 'password'));
    }

    public function testValidationFails()
    {
        $data = factory(\App\User::class)->make()->toArray();
        $this->json('POST', 'api/users', $data)->assertJsonStructure(['errors'])->assertStatus(400);
    }

    public function testShow()
    {
        $user = factory(\App\User::class)->make();
        $data = array_add($user->toArray(), 'password', 'mysecret');
        $this->json('POST', 'api/users', $data);
        $this->json('GET', "api/users/{$data['email']}")->assertJson(array_except($data, 'password'));
    }

    public function testNotFound()
    {
        $this->json('GET', 'api/users/blah')->assertJsonStructure(['error'])->assertStatus(404);
    }

    public function testUpdate()
    {
        $user = factory(\App\User::class)->make();
        $data = array_add($user->toArray(), 'password', 'mysecret');
        $this->json('POST', 'api/users', $data);
        $data['name'] = 'updated';
        $this->json('PUT', "api/users/{$data['email']}",
            array_only($data, ['name']))->assertJson(array_except($data, 'password'));
    }

    public function testDestroy()
    {
        $user = factory(\App\User::class)->make();
        $data = array_add($user->toArray(), 'password', 'mysecret');
        $this->json('POST', 'api/users', $data);
        $this->json('DELETE', "api/users/{$data['email']}")->assertJsonStructure(['message'])->assertStatus(200);
    }

    public function testActivate()
    {
        $user = $this->storeUser();
        $this->json('GET', route('activate_user', [
            'id' => $user->id,
            'code' => $user->activation_code
        ]))->assertJsonStructure(['message'])->assertStatus(200);
    }

    public function testActivateFails()
    {
        $user = $this->storeUser();
        $this->json('GET', route('activate_user', [
            'id' => $user->id,
            'code' => 1,
        ]))->assertJsonStructure(['error'])->assertStatus(300);
    }
}
