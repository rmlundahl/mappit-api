<?php

namespace Tests\API\Item;

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
                    ['location' => 'Amsterdam']
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
}
