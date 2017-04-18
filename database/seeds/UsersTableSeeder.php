<?php

use App\User;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\Seeder;

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

        $user = (new User([
            'name'         => 'other guy',
            'email'        => 'hanifi@gmail.com',
            'password'     => 'password',
            'phone_number' => '213555669999',
            'active'       => true,
        ], \App::make(Hasher::class)))->save();
    }
}
