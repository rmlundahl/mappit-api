<?php

namespace Tests\API\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

use App\Models\User;
use App\Models\Item;

class GetItemsTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_all_items__no_items()
    {
        $response = $this->getJson('/api/v1/nl/items');
        $response
            ->assertStatus(200)
            ->assertJsonCount(0);
    }

    
    public function test_get_all_items__from_a_language()
    {
        $items = Item::factory()->create(['id'=>1, 'language'=>'nl']);
        $items = Item::factory()->create(['id'=>1, 'language'=>'en']);
        $items = Item::factory()->create(['id'=>2, 'language'=>'nl']);
        $items = Item::factory()->create(['id'=>2, 'language'=>'en']);
        $items = Item::factory()->create(['id'=>3, 'language'=>'nl']);
        
        \App::setLocale('en');
        
        $response = $this->getJson('/api/v1/nl/items');
        $response
            ->assertStatus(200)
            ->assertJsonCount(2);
    }

    public function test_find_item()
    {
        $items = Item::factory()->count(3)->create();
        $item  = Item::factory()->create(['id'=>123, 'language'=>'nl']);

        $response = $this->getJson('/api/v1/nl/items/123');

        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json
                ->count(12)    
                ->where('id', 123)
                ->where('language', 'nl')
                ->etc()
            );
    }

    public function test_get_all_items_of_type()
    {
        $items = Item::factory()->create(['id'=>1, 'language'=>'nl', 'item_type_id'=>10]);
        $items = Item::factory()->create(['id'=>1, 'language'=>'en', 'item_type_id'=>10]);
        $items = Item::factory()->create(['id'=>2, 'language'=>'nl', 'item_type_id'=>20]);
        $items = Item::factory()->create(['id'=>2, 'language'=>'en', 'item_type_id'=>20]);
        $items = Item::factory()->create(['id'=>3, 'language'=>'nl', 'item_type_id'=>10]);
        $items = Item::factory()->create(['id'=>4, 'language'=>'nl', 'item_type_id'=>20]);
        
        \App::setLocale('nl');
        
        $response = $this->getJson('/api/v1/nl/items?item_type_id=20');
        $response
            ->assertJson([
                [
                "id" => 2,
                "language" => "nl",
                "item_type_id" => 20
                ],
                [
                "id" => 4,
                "language" => "nl",
                "item_type_id" => 20
                ],
            ]);
    }
}
