<?php

use Illuminate\Database\Seeder;

class LocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('locations')->insert([
            'city'      => 'Cape Town',
            'province'  => 'Western Cape',
            'country'   => 'South Africa',
            'created_at'    => now()
        ]);

        DB::table('locations')->insert([
            'city'      => 'Rivonia, Johannesburg',
            'province'  => 'Gauteng',
            'country'   => 'South Africa',
            'created_at'    => now()
        ]);

        DB::table('locations')->insert([
            'city'      => 'Randburg, Johannesburg',
            'province'  => 'Gauteng',
            'country'   => 'South Africa',
            'created_at'    => now()
        ]);

        DB::table('locations')->insert([
            'city'      => 'Midrand, Johannesburg',
            'province'  => 'Gauteng',
            'country'   => 'South Africa',
            'created_at'    => now()
        ]);

        DB::table('locations')->insert([
            'city'      => 'Illovo, Johannesburg',
            'province'  => 'Gauteng',
            'country'   => 'South Africa',
            'created_at'    => now()
        ]);

        DB::table('locations')->insert([
            'city'      => 'Durban',
            'province'  => 'KwaZulu-Natal',
            'country'   => 'South Africa',
            'created_at'    => now()
        ]);

        DB::table('locations')->insert([
            'city'      => 'Pretoria',
            'province'  => 'Gauteng',
            'country'   => 'South Africa',
            'created_at'    => now()
        ]);

        DB::table('locations')->insert([
            'city'      => 'Port Elizabeth',
            'province'  => 'Eastern Cape',
            'country'   => 'South Africa',
            'created_at'    => now()
        ]);

        DB::table('locations')->insert([
            'city'      => 'Pietermaritzburg',
            'province'  => 'KwaZulu-Natal',
            'country'   => 'South Africa',
            'created_at'    => now()
        ]);

        DB::table('locations')->insert([
            'city'      => 'Benoni',
            'province'  => 'Gauteng',
            'country'   => 'South Africa',
            'created_at'    => now()
        ]);

        DB::table('locations')->insert([
            'city'      => 'Tembisa',
            'province'  => 'Gauteng',
            'country'   => 'South Africa',
            'created_at'    => now()
        ]);

        DB::table('locations')->insert([
            'city'      => 'East London',
            'province'  => 'Eastern Cape',
            'country'   => 'South Africa',
            'created_at'    => now()
        ]);

        DB::table('locations')->insert([
            'city'      => 'Vereeniging',
            'province'  => 'Gauteng',
            'country'   => 'South Africa',
            'created_at'    => now()
        ]);

        DB::table('locations')->insert([
            'city'      => 'Bloemfontein',
            'province'  => 'Free State',
            'country'   => 'South Africa',
            'created_at'    => now()
        ]);

        DB::table('locations')->insert([
            'city'      => 'Boksburg',
            'province'  => 'Gauteng',
            'country'   => 'South Africa',
            'created_at'    => now()
        ]);

        DB::table('locations')->insert([
            'city'      => 'Welkom',
            'province'  => 'Free State',
            'country'   => 'South Africa',
            'created_at'    => now()
        ]);

        DB::table('locations')->insert([
            'city'      => 'Newcastle',
            'province'  => 'KwaZulu-Natal',
            'country'   => 'South Africa',
            'created_at'    => now()
        ]);

        DB::table('locations')->insert([
            'city'      => 'Soweto',
            'province'  => 'Gauteng',
            'country'   => 'South Africa',
            'created_at'    => now()
        ]);

        DB::table('locations')->insert([
            'city'      => 'Diepsloot',
            'province'  => 'Gauteng',
            'country'   => 'South Africa',
            'created_at'    => now()
        ]);

        DB::table('locations')->insert([
            'city'      => 'Randburg',
            'province'  => 'Gauteng',
            'country'   => 'South Africa',
            'created_at'    => now()
        ]);

        DB::table('locations')->insert([
            'city'      => 'Krugersdorp',
            'province'  => 'Gauteng',
            'country'   => 'South Africa',
            'created_at'    => now()
        ]);
    }
}
