<?php

namespace Tests\Feature\API\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Item;

class CreateItemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_create_item()
    {
        $user = User::factory()->create();
        
        $this->actingAs($user)
            ->json('POST', '/api/v1/nl/items', [
                'language' => 'nl',
                'item_type_id' => 10,
                'name' => 'New Name',
                'user_id' => $user->id,
                'status_id' => 10,
            ])->assertStatus( 201 );
        
        
        $this->assertDatabaseHas('items', [
            'name' => 'New Name',
            'slug' => 'new-name',
            'user_id' => $user->id
            ]);
    }

    public function test_create_item_with_item_properties()
    {
        $user = User::factory()->create();
        
        $this->actingAs($user)
            ->json('POST', '/api/v1/nl/items', [
                'language' => 'nl',
                'item_type_id' => 10,
                'name' => 'New Name',
                'user_id' => $user->id,
                'status_id' => 10,
                'item_properties' => [
                    'location' => 'Amsterdam'
                ]
            ])->assertStatus( 201 );
        
        
        $this->assertDatabaseHas('items', [
            'id' => '2',
            'name' => 'New Name',
            'slug' => 'new-name',
            'user_id' => $user->id
            ]);
        
        $this->assertDatabaseHas('item_properties', [
            'language' => 'nl',
            'item_id' => '2',
            'key' => 'location',
            'value' => 'Amsterdam',
            'status_id' => 10            
            ]);
    }

    public function test_create_item_of_type_collection()
    {
        $user = User::factory()->create();
        
        $this->actingAs($user)
            ->json('POST', '/api/v1/nl/items', [
                'language' => 'nl',
                'item_type_id' => 30,
                'name' => 'New Collection',
                'user_id' => $user->id,
                'status_id' => 10,
                'item_properties' => [
                    'location' => 'Amsterdam'
                ],
                'collection_items' => '56,88,124'
            ])->assertStatus( 201 );
        
        
        $this->assertDatabaseHas('items', [
            'id' => '3',
            'name' => 'New Collection',
            'slug' => 'new-collection',
            'user_id' => $user->id
            ]);
        
        $this->assertDatabaseHas('item_properties', [
            'language' => 'nl',
            'item_id' => '3',
            'key' => 'location',
            'value' => 'Amsterdam',
            'status_id' => 10            
            ]);

        $this->assertDatabaseHas('item_collection', [
            'item_id' => '3',
            'collection_item_id' => '88' 
            ]);
    }
}
