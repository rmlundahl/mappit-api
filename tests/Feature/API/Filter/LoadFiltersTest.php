<?php

namespace Tests\Feature\API\Filter;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

use App\Models\User;
use App\Models\Filter;

use DB;

class LoadFiltersTest extends TestCase
{
    use RefreshDatabase;

   
    public function test_load_all_filters__from_a_language()
    {
        DB::table('filters')->truncate();

        $filters = Filter::factory()->create(['id'=>1, 'language'=>'nl']);
        $filters = Filter::factory()->create(['id'=>1, 'language'=>'en']);
        $filters = Filter::factory()->create(['id'=>2, 'language'=>'nl']);
        $filters = Filter::factory()->create(['id'=>2, 'language'=>'en']);
        $filters = Filter::factory()->create(['id'=>3, 'language'=>'nl']);

        \App::setLocale('en');
        
        $response = $this->getJson('/api/v1/nl/filters');
        $response
            ->assertStatus(200)
            ->assertJsonCount(3);
    }

}
