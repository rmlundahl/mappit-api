<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use DB;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function clearTables() {
        DB::table('items')->truncate();
        DB::table('item_properties')->truncate();
        DB::table('users')->truncate();
        DB::table('groups')->truncate();
    }
}
