<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
            'name' => 'Admin',
            'email' => 'mappit-admin@imachine.nl',
            'password' => Hash::make('mappit-admin@imachine.nl'),
            'group_id' => 1,
            'is_group_admin' => 1,
            'role' => 'administrator',
            'created_at' => '2020-12-24',
            'updated_at' => '2020-12-24',
            'status_id' => 20,
        ]);
    }
}
