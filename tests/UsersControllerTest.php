<?php

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
            'password' => 'mock',
            'phone_number' => '213666666666',
        ];
    }

    private function storeUser()
    {
        return $this->userService->store($this->storeUserData());
    }
    public function testStore()
    {
        $user = factory(App\User::class)->make();
        $data = array_add($user->toArray(), 'password', 'mysecret');
        $this->json('POST', 'api/users', $data)->seeJsonContains(array_except($data, 'password'));
    }

    public function testValidationFails()
    {
        $data = factory(App\User::class)->make()->toArray();
        $this->json('POST', 'api/users', $data)->seeJsonStructure(['message'])->seeStatusCode(400);
    }

    public function testShow()
    {
        $user = factory(App\User::class)->make();
        $data = array_add($user->toArray(), 'password', 'mysecret');
        $this->json('POST', 'api/users', $data);
        $this->json('GET', "api/users/{$data['email']}")->seeJsonContains(array_except($data, 'password'));
    }

    public function testNotFound()
    {
        $this->json('GET', 'api/users/blah')->seeJsonStructure(['message'])->seeStatusCode(404);
    }

    public function testUpdate()
    {
        $user = factory(App\User::class)->make();
        $data = array_add($user->toArray(), 'password', 'mysecret');
        $this->json('POST', 'api/users', $data);
        $data['name'] = 'updated';
        $this->json('PUT', "api/users/{$data['email']}",
            array_only($data, ['name']))->seeJsonContains(array_except($data, 'password'));
    }

    public function testDestroy()
    {
        $user = factory(App\User::class)->make();
        $data = array_add($user->toArray(), 'password', 'mysecret');
        $this->json('POST', 'api/users', $data);
        $this->json('DELETE', "api/users/{$data['email']}")->seeJsonStructure(['message'])->seeStatusCode(200);
    }

    public function testActivate()
    {
        $user = $this->storeUser();
        $this->json('GET', route('activate_user', [
            'id' => $user->id,
            'code' => $user->activation_code
        ]))->seeJsonStructure(['message'])->seeStatusCode(200);
    }

    public function testActivateFails()
    {
        $user = $this->storeUser();
        $this->json('GET', route('activate_user', [
            'id' => $user->id,
            'code' => 1,
        ]))->seeJsonStructure(['message'])->seeStatusCode(300);
    }
}
