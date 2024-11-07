<?php

namespace Tests\Feature\API\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\User;
use App\Models\Group;
use App\Models\Item;

use App, DB;

class GetCollectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_all_collections__no_items(): void
    {
        $this->clearTables();
        
        $response = $this->getJson('/api/v1/nl/collections');
        $response
            ->assertStatus(200)
            ->assertJsonCount(0);
    }

    public function test_get_all_collections__from_a_language(): void
    {
        $collection = Item::factory()->create(['id'=>100, 'language'=>'nl', 'name'=>'Ambassadeurs', 'slug'=>'ambassadeurs', 'item_type_id'=>30]);
        $collection = Item::factory()->create(['id'=>100, 'language'=>'en', 'name'=>'Ambassadors', 'slug'=>'ambassadors', 'item_type_id'=>30]);
        $collection = Item::factory()->create(['id'=>101, 'language'=>'nl', 'name'=>'Top 10', 'slug'=>'top-10', 'item_type_id'=>30]);
        
        $group = Group::factory()->create(['id'=>1]);
        $user = User::factory()->create(['id'=>1, 'group_id'=>1]);
        
        $item = Item::factory()->create(['id'=>1, 'language'=>'nl', 'name'=>'item 1 - nl', 'user_id'=>1]);
        $item = Item::factory()->create(['id'=>1, 'language'=>'en', 'name'=>'item 1 - en', 'user_id'=>1]);
        $item = Item::factory()->create(['id'=>2, 'language'=>'nl', 'name'=>'item 2 - nl', 'user_id'=>1]);
        $item = Item::factory()->create(['id'=>2, 'language'=>'en', 'name'=>'item 2 - en', 'user_id'=>1]);
        $item = Item::factory()->create(['id'=>3, 'language'=>'nl', 'name'=>'item 3 - nl', 'user_id'=>1]);
       
        DB::table('item_properties')->insert(['id'=>1, 'language'=>'nl', 'item_id'=>1, 'key'=>'uitgelichte_afbeelding', 'value'=> '/storage/items/1018_universiteit.jpg']);

        DB::table('item_collection')->insert(['item_id'=>1, 'collection_item_id'=>100]);
        DB::table('item_collection')->insert(['item_id'=>3, 'collection_item_id'=>100]);

        App::setLocale('nl');
        
        $response = $this->getJson('/api/v1/nl/collections');
        $response
            ->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJsonFragment(['name' => 'item 1 - nl'])
            ->assertJsonFragment(['name' => 'item 3 - nl'])
            ->assertJsonMissing (['name' => 'item 1 - en'])
            ->assertJsonMissing (['name' => 'item 2 - nl'])
            ->assertJsonMissing (['name' => 'item 2 - en'])
            ->assertJsonFragment (['collection_items' => []]);

    }

}
