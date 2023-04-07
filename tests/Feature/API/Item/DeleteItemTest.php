<?php

namespace Tests\Feature\API\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

use App\Models\User;
use App\Models\Item;

class DeleteItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_delete_item()
    {
        $user = User::factory()->create();       
        $items = Item::factory()->count(3)->create();
        $item  = Item::factory()->create(['id'=>123, 'language'=>'nl']);

        $response = $this->actingAs($user)->deleteJson('/api/v1/nl/items/123', ['id'=>123, 'language'=>'nl']);

        $response->assertStatus(204);

        $this->assertDatabaseHas('items', ['id' => 123, 'language' => 'nl', 'status_id' => 99]);            
    }

    public function test_delete_item__do_not_delete_non_existing_id()
    {
        $user = User::factory()->create();
        $item  = Item::factory()->create(['id'=>123, 'language'=>'nl', 'status_id'=>20]);
        $item  = Item::factory()->create(['id'=>123, 'language'=>'en', 'status_id'=>20]);
        
        $response = $this->actingAs($user)->deleteJson('/api/v1/nl/items/123', ['id'=>456, 'language'=>'en'])
            ->assertStatus(404);

        $this->assertDatabaseHas('items', ['id'=>123, 'language'=>'nl', 'status_id'=>20]);
        $this->assertDatabaseHas('items', ['id'=>123, 'language'=>'en', 'status_id'=>20]);
           
    }
}
