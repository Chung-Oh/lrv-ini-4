<?php

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
        factory(\App\User::class, 1)->states('user')->create();
        factory(\App\User::class, 1)->states('admin')->create([
            'email' => 'admin@user.com',
            'phone' => '11988775544',
            'cpf'   => '73228699981'
        ]);
        factory(\App\User::class, 1)->states('user')->create([
            'email' => 'user@user.com'
        ]);
    }
}
