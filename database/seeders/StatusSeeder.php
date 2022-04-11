<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('statuses')->insert([
            [
                'id' => 1,
                'status' => 'new',
                'description' => 'Status for records new in the database.'
            ],
            [
                'id' => 10,
                'status' => 'draft',
                'description' => 'Status for records being edited. In a CMS these records would be visible, but on the live site they are not.'
            ],
            [
                'id' => 20,
                'status' => 'published',
                'description' => 'Status for records that are published and visible.'
            ],
            [
                'id' => 30,
                'status' => 'future',
                'description' => 'Scheduled to be published in a future date.'
            ],
            [
                'id' => 50,
                'status' => 'pending',
                'description' => 'Awaiting to be published.'
            ],
            [
                'id' => 90,
                'status' => 'archived',
                'description' => 'Status for records that are archived. In a CMS these records would be visible, but on the live site they are not.'
            ],
            [
                'id' => 99,
                'status' => 'deleted',
                'description' => 'Status for records that are soft deleted. In a CMS as well as on the live site these records would not be visible.'
            ],
            [
                'id' => 200,
                'status' => 'template',
                'description' => 'Status for records that serve as template or setting.'
            ],
        ]);
    }
}
