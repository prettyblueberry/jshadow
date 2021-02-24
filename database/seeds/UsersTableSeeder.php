<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'role_id'           => 1,
            'name'              => 'Marcus Christiansen',
            'email'             => 'christiansen.marcus@gmail.com',
            'email_verified_at' => now(),
            'password'          => bcrypt('5[9c5{SEDTtMju+5E#6'),
            'created_at'        => now()
        ]);

        DB::table('users')->insert([
            'role_id'           => 2,
            'name'              => 'Meghnaaz Williams',
            'email'             => 'meghnaaz@gmail.com',
            'email_verified_at' => now(),
            'password'          => bcrypt('password'),
            'created_at'        => now()
        ]);
    }
}
