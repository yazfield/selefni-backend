<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Contracts\Hashing\Hasher;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = (new User([
            'name' => 'yazid',
            'email' => 'hanifi.yazid@gmail.com',
            'password' => 'password',
            'phone_number' => '213555669988',
            'active' => true,
        ], \App::make(Hasher::class)))->save();
    }
}
