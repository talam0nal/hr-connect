<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Manager',
            'email' => 'manager@hrconnect.ru',
            'password' => Hash::make('22312231q345'),
            'is_manager' => 1,
        ]);
    }
}
