<?php

namespace Tests\Feature\API\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Item;

class UpdateItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_item()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create([
            'id' => 23,
            'language' => 'nl',
            'item_type_id' => 10,
            'name' => 'New Name',
            'slug' => 'new-name',
            'user_id' => $user->id,
            'status_id' => 10,
        ]);

        $this->actingAs($user)
            ->json('PUT', '/api/v1/nl/items/23', 
            [
                'id' => 23,
                'language' => 'nl',
                'name' => 'Updated Name',
                // 'slug' => 'updated-name',
                // 'status_id' => 20,
            ])->assertStatus( 200 );
        
        
        $this->assertDatabaseHas('items', [
            'language' => 'nl',
            'name' => 'Updated Name',
            'slug' => 'updated-name'
            ]);
    }

    public function test_update_item__with_existing_slug()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create([
            'id' => 1,
            'language' => 'nl',
            'item_type_id' => 10,
            'name' => 'New Name',
            'slug' => 'new-name',
        ]);

        $item = Item::factory()->create([
            'id' => 23,
            'language' => 'nl',
            'item_type_id' => 10,
            'name' => 'Second Name',
            'slug' => 'new-name',
            'user_id' => $user->id,
            'status_id' => 20,
        ]);

        $this->actingAs($user)
            ->json('PUT', '/api/v1/nl/items/23', 
            [
                'id' => 23,
                'language' => 'nl',
                'name' => 'Second Name',
                'slug' => 'new-name',
            ])->assertStatus( 200 );
        
        
        $this->assertDatabaseHas('items', [
            'id' => 23,
            'language' => 'nl',
            'name' => 'Second Name',
            'slug' => 'new-name-2'
            ]);
    }
}
