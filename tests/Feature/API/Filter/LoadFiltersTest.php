<?php

namespace Tests\API\Filter;

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
            ->assertJsonCount(2);
    }

    public function test_find_filter()
    {
        $filters = Filter::factory()->count(3)->create();
        $filter  = Filter::factory()->create(['id'=>123, 'language'=>'nl']);

        $response = $this->getJson('/api/v1/nl/filters/123');

        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json
                ->count(8)    
                ->where('id', 123)
                ->where('language', 'nl')
                ->etc()
            );
    }

}
