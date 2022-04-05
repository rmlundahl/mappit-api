<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('groups')->insert([
            [
                'parent_id' => null,
                'name' => 'root',
                'description' => 'Root group for all users',
                'created_at' => '2020-12-24',
                'updated_at' => '2020-12-24',
                'status_id' => 20,
            ],
            [
                'parent_id' => 1,
                'name' => 'First Group',
                'description' => '',
                'created_at' => '2020-12-24',
                'updated_at' => '2020-12-24',
                'status_id' => 20,
            ],
            [
                'parent_id' => 1,
                'name' => 'Second Group',
                'description' => '',
                'created_at' => '2020-12-24',
                'updated_at' => '2020-12-24',
                'status_id' => 20,
            ]
        ]);

    }
}
