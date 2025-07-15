<?php

namespace Tests\Feature\API\ItemProperty;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\ItemProperty;

class BulkUpdateItemPropertiesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->clearTables();
    }

    public function test_bulk_update_item_properties_success(): void
    {
        // Create a user
        $user = User::factory()->create(['role' => 'administrator']);
        
        // Create multiple items
        $item1 = Item::factory()->create([
            'id' => 1001,
            'language' => 'nl',
            'status_id' => 20,
            'user_id' => $user->id
        ]);
        
        $item2 = Item::factory()->create([
            'id' => 1002,
            'language' => 'nl',
            'status_id' => 20,
            'user_id' => $user->id
        ]);
        
        // Create item properties with the same key and value
        ItemProperty::factory()->create([
            'item_id' => $item1->id,
            'language' => 'nl',
            'key' => 'partner_naam',
            'value' => 'Original Partner Name',
            'status_id' => 20,
        ]);
        
        ItemProperty::factory()->create([
            'item_id' => $item2->id,
            'language' => 'nl',
            'key' => 'partner_naam',
            'value' => 'Original Partner Name',
            'status_id' => 20,
        ]);
        
        // Create a property with different value
        ItemProperty::factory()->create([
            'item_id' => $item1->id,
            'language' => 'nl',
            'key' => 'partner_naam',
            'value' => 'Different Partner Name',
            'status_id' => 20,
        ]);

        // Make the bulk update request - should update both properties with 'Original Partner Name'
        $response = $this->actingAs($user)
            ->json('POST', '/api/v1/nl/item-properties/bulk-update', [
                'key' => 'partner_naam',
                'old_value' => 'Original Partner Name',
                'new_value' => 'Updated Partner Name'
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'affected_count' => 2,
            'errors' => []
        ]);

        // Verify database changes - both items should be updated
        $this->assertDatabaseHas('item_properties', [
            'item_id' => $item1->id,
            'language' => 'nl',
            'key' => 'partner_naam',
            'value' => 'Updated Partner Name'
        ]);
        
        $this->assertDatabaseHas('item_properties', [
            'item_id' => $item2->id,
            'language' => 'nl',
            'key' => 'partner_naam',
            'value' => 'Updated Partner Name'
        ]);
        
        // The property with different value should not be updated
        $this->assertDatabaseHas('item_properties', [
            'item_id' => $item1->id,
            'language' => 'nl',
            'key' => 'partner_naam',
            'value' => 'Different Partner Name'
        ]);
    }

    public function test_bulk_update_with_language_filter(): void
    {
        // Create a user
        $user = User::factory()->create(['role' => 'administrator']);
        
        // Create items with different languages
        $item1 = Item::factory()->create([
            'id' => 1001,
            'language' => 'nl',
            'status_id' => 20,
            'user_id' => $user->id
        ]);
        
        $item2 = Item::factory()->create([
            'id' => 1002,
            'language' => 'en',
            'status_id' => 20,
            'user_id' => $user->id
        ]);
        
        // Create item properties with the same key and value but different languages
        ItemProperty::factory()->create([
            'item_id' => $item1->id,
            'language' => 'nl',
            'key' => 'sector_naam',
            'value' => 'Target Value',
            'status_id' => 20,
        ]);
        
        ItemProperty::factory()->create([
            'item_id' => $item2->id,
            'language' => 'en',
            'key' => 'sector_naam',
            'value' => 'Target Value',
            'status_id' => 20,
        ]);

        // Make the bulk update request with language filter
        $response = $this->actingAs($user)
            ->json('POST', '/api/v1/nl/item-properties/bulk-update', [
                'key' => 'sector_naam',
                'old_value' => 'Target Value',
                'new_value' => 'Updated Value',
                'language' => 'nl' // Only update Dutch properties
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'affected_count' => 1,
            'errors' => []
        ]);

        // Verify database changes - only the Dutch property should be updated
        $this->assertDatabaseHas('item_properties', [
            'item_id' => $item1->id,
            'language' => 'nl',
            'key' => 'sector_naam',
            'value' => 'Updated Value'
        ]);
        
        // The English property should remain unchanged
        $this->assertDatabaseHas('item_properties', [
            'item_id' => $item2->id,
            'language' => 'en',
            'key' => 'sector_naam',
            'value' => 'Target Value'
        ]);
    }

    public function test_bulk_update_item_properties_no_matches(): void
    {
        $user = User::factory()->create(['role' => 'administrator']);
        
        // Make the bulk update request with non-existent key/value combination
        $response = $this->actingAs($user)
            ->json('POST', '/api/v1/nl/item-properties/bulk-update', [
                'key' => 'nonexistent_key',
                'old_value' => 'nonexistent_value',
                'new_value' => 'New Value'
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'affected_count' => 0,
            'errors' => []
        ]);
    }

    public function test_bulk_update_item_properties_unauthorized(): void
    {
        // Create a regular user (non-admin)
        $user = User::factory()->create(['role' => 'user']);
        
        // Make the bulk update request
        $response = $this->actingAs($user)
            ->json('POST', '/api/v1/nl/item-properties/bulk-update', [
                'key' => 'test_key',
                'old_value' => 'Test Value',
                'new_value' => 'Updated Value'
            ]);

        $response->assertStatus(403);
    }

    public function test_bulk_update_item_properties_validation_error(): void
    {
        $user = User::factory()->create(['role' => 'administrator']);
        
        // Missing required fields
        $response = $this->actingAs($user)
            ->json('POST', '/api/v1/nl/item-properties/bulk-update', [
                'key' => 'test_key',
                // Missing old_value
                'new_value' => 'Updated Value'
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['old_value']);
    }
}