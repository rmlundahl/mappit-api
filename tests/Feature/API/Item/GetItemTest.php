<?php

namespace Tests\Feature\API\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

use App\Models\User;
use App\Models\Group;
use App\Models\Item;

use App;

class GetItemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->clearTables();
    }

    public function test_get_all_items__no_items()
    {
        $response = $this->getJson('/api/v1/nl/items');
        $response
            ->assertStatus(200)
            ->assertJsonCount(0);
    }

    
    public function test_get_all_items__from_a_language()
    {
        $user = User::factory()->create(['id'=>123]);

        $items = Item::factory()->create(['id'=>1, 'language'=>'nl', 'user_id'=>123]);
        $items = Item::factory()->create(['id'=>1, 'language'=>'en', 'user_id'=>123]);
        $items = Item::factory()->create(['id'=>2, 'language'=>'nl', 'user_id'=>123]);
        $items = Item::factory()->create(['id'=>2, 'language'=>'en', 'user_id'=>123]);
        $items = Item::factory()->create(['id'=>3, 'language'=>'nl', 'user_id'=>123]);
        
        App::setLocale('en');
        
        $response = $this->getJson('/api/v1/nl/items');
        $response
            ->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_get_all_markers__from_a_language_with_fallback_language()
    {
        $user = User::factory()->create(['id'=>123]);
        
        $items = Item::factory()->create(['id'=>1, 'language'=>'nl', 'name'=>'1-nl', 'status_id'=>20, 'user_id'=>123]); // X because 'en' record instead
        $items = Item::factory()->create(['id'=>1, 'language'=>'en', 'name'=>'1-en', 'status_id'=>20, 'user_id'=>123]); // V
        $items = Item::factory()->create(['id'=>2, 'language'=>'nl', 'name'=>'2-nl', 'status_id'=>20, 'user_id'=>123]); // X because 'en' record instead
        $items = Item::factory()->create(['id'=>2, 'language'=>'en', 'name'=>'2-en', 'status_id'=>20, 'user_id'=>123]); // V
        $items = Item::factory()->create(['id'=>3, 'language'=>'nl', 'name'=>'3-nl', 'status_id'=>10, 'user_id'=>123]); // X because status is wrong
        $items = Item::factory()->create(['id'=>4, 'language'=>'nl', 'name'=>'4-nl', 'status_id'=>99, 'user_id'=>123]); // X because deleted
        $items = Item::factory()->create(['id'=>5, 'language'=>'nl', 'name'=>'5-nl', 'status_id'=>20, 'user_id'=>123]); // V 
        
        $response = $this->getJson('/api/v1/nl/items/all_markers?language=en');
   
        $response
            ->assertStatus(200)
            ->assertJsonCount(3)
            ->assertJsonFragment(
                ['id'=>1, 'name'=>'1-en', 'status_id'=>20],
                ['id'=>2, 'name'=>'2-en', 'status_id'=>20],
                ['id'=>5, 'name'=>'5-nl', 'status_id'=>20],
            )
            ->assertJsonMissing(
                ['name'=>'1-nl'],
                ['name'=>'2-nl'],
                ['name'=>'3-nl'],
                ['name'=>'4-nl'],
            );
            
    }

    public function test_get_all_from_user__author_sees_own_items_only()
    {
        $user = User::factory()->create(['id'=>123, 'role'=>'author', 'is_group_admin'=>0]);

        $items = Item::factory()->create(['id'=>11, 'language'=>'nl', 'user_id'=>123]);
        $items = Item::factory()->create(['id'=>11, 'language'=>'en', 'user_id'=>123]);
        $items = Item::factory()->create(['id'=>12, 'language'=>'nl', 'user_id'=>2]);
        $items = Item::factory()->create(['id'=>12, 'language'=>'en', 'user_id'=>2]);
        $items = Item::factory()->create(['id'=>13, 'language'=>'nl', 'user_id'=>123]);
        
        App::setLocale('nl');
        
        $response = $this->actingAs($user)->getJson('/api/v1/nl/items/all_from_user');
        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json
                ->has(2)
                ->first( fn ($json) =>
                    $json->where('id', 11)
                    ->where('user_id', 123)
                    ->etc()
                )
            )
            ->assertJsonMissing(['user_id'=>2]);
    }

    public function test_get_all_from_user__author_is_group_admin_and_sees_own_group_items_only()
    {
        $user1 = User::factory()->create(['id'=>123, 'group_id'=>123, 'role'=>'author', 'is_group_admin'=>1]);
        $user2 = User::factory()->create(['id'=>2,   'group_id'=>123, 'role'=>'author', 'is_group_admin'=>0]);
        $user3 = User::factory()->create(['id'=>3,   'group_id'=>3,   'role'=>'author', 'is_group_admin'=>0]);

        $items = Item::factory()->create(['id'=>11, 'language'=>'nl', 'user_id'=>123]);
        $items = Item::factory()->create(['id'=>11, 'language'=>'en', 'user_id'=>123]);
        $items = Item::factory()->create(['id'=>12, 'language'=>'nl', 'user_id'=>2]);
        $items = Item::factory()->create(['id'=>12, 'language'=>'en', 'user_id'=>2]);
        $items = Item::factory()->create(['id'=>13, 'language'=>'nl', 'user_id'=>3]);
        
        App::setLocale('nl');
        
        $response = $this->actingAs($user1)->getJson('/api/v1/nl/items/all_from_user');

        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json
                ->has(2)
                ->first( fn ($json) =>
                    $json->where('id', 11)
                    ->where('user_id', 123)
                    ->where('language', 'nl')
                    ->etc()
                )
            )
            ->assertJsonFragment(['user_id'=>2])
            ->assertJsonMissing(['user_id'=>3]);
    }

    public function test_get_all_from_user__editor_sees_own_group_and_descendants_items_only()
    {
        $group1 = Group::factory()->create(['id'=>123, 'parent_id'=>1]);
        $group2 = Group::factory()->create(['id'=>124, 'parent_id'=>123]);
        $group3 = Group::factory()->create(['id'=>301, 'parent_id'=>300]);

        $user1 = User::factory()->create(['id'=>123, 'group_id'=>123, 'role'=>'editor', 'is_group_admin'=>0]);
        $user2 = User::factory()->create(['id'=>2,   'group_id'=>123, 'role'=>'author', 'is_group_admin'=>0]);
        $user3 = User::factory()->create(['id'=>3,   'group_id'=>124, 'role'=>'author', 'is_group_admin'=>0]);
        $user3 = User::factory()->create(['id'=>4,   'group_id'=>301, 'role'=>'author', 'is_group_admin'=>0]);

        $items = Item::factory()->create(['id'=>11, 'language'=>'nl', 'user_id'=>123]);
        $items = Item::factory()->create(['id'=>11, 'language'=>'en', 'user_id'=>123]);
        $items = Item::factory()->create(['id'=>12, 'language'=>'nl', 'user_id'=>2]);
        $items = Item::factory()->create(['id'=>13, 'language'=>'nl', 'user_id'=>3]);
        $items = Item::factory()->create(['id'=>14, 'language'=>'nl', 'user_id'=>4]);
        
        App::setLocale('nl');
        
        $response = $this->actingAs($user1)->getJson('/api/v1/nl/items/all_from_user');

        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json
                ->has(3)
                ->first( fn ($json) =>
                    $json->where('id', 11)
                    ->where('user_id', 123)
                    ->where('language', 'nl')
                    ->etc()
                )
            )
            ->assertJsonFragment(['user_id'=>2])
            ->assertJsonFragment(['user_id'=>3])
            ->assertJsonMissing(['user_id'=>4]);
    }

    public function test_get_all_from_user__administrator_sees_all_items()
    {
        $group0 = Group::factory()->create(['id'=>1,   'parent_id'=>null]);
        $group1 = Group::factory()->create(['id'=>123, 'parent_id'=>1]);
        $group2 = Group::factory()->create(['id'=>124, 'parent_id'=>123]);
        $group3 = Group::factory()->create(['id'=>301, 'parent_id'=>2]);

        $user1 = User::factory()->create(['id'=>123, 'group_id'=>1,   'role'=>'administrator', 'is_group_admin'=>0]);
        $user2 = User::factory()->create(['id'=>2,   'group_id'=>123, 'role'=>'author', 'is_group_admin'=>0]);
        $user3 = User::factory()->create(['id'=>3,   'group_id'=>124, 'role'=>'author', 'is_group_admin'=>0]);
        $user3 = User::factory()->create(['id'=>4,   'group_id'=>301, 'role'=>'author', 'is_group_admin'=>0]);
        $user3 = User::factory()->create(['id'=>5,   'group_id'=>3,   'role'=>'author', 'is_group_admin'=>0]);

        $items = Item::factory()->create(['id'=>11, 'language'=>'nl', 'user_id'=>123]);
        $items = Item::factory()->create(['id'=>11, 'language'=>'en', 'user_id'=>123]);
        $items = Item::factory()->create(['id'=>12, 'language'=>'nl', 'user_id'=>2]);
        $items = Item::factory()->create(['id'=>13, 'language'=>'nl', 'user_id'=>3]);
        $items = Item::factory()->create(['id'=>14, 'language'=>'nl', 'user_id'=>4]);
        
        App::setLocale('nl');
        
        $response = $this->actingAs($user1)->getJson('/api/v1/nl/items/all_from_user');

        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json
                ->has(3)
                ->first( fn ($json) =>
                    $json->where('id', 11)
                    ->where('user_id', 123)
                    ->where('language', 'nl')
                    ->etc()
                )
            )
            ->assertJsonFragment(['user_id'=>123])
            ->assertJsonFragment(['user_id'=>2])
            ->assertJsonFragment(['user_id'=>3])
            ->assertJsonMissing(['user_id'=>4])
            ->assertJsonMissing(['user_id'=>5]);
    }

    public function test_get_all_markers__from_a_language()
    {
        $user = User::factory()->create(['id'=>123]);

        $items = Item::factory()->create(['id'=>1, 'language'=>'nl', 'status_id'=>20, 'user_id'=>123]);
        $items = Item::factory()->create(['id'=>1, 'language'=>'en', 'status_id'=>20, 'user_id'=>123]);
        $items = Item::factory()->create(['id'=>2, 'language'=>'nl', 'status_id'=>20, 'user_id'=>123]);
        $items = Item::factory()->create(['id'=>2, 'language'=>'en', 'status_id'=>20, 'user_id'=>123]);
        $items = Item::factory()->create(['id'=>3, 'language'=>'nl', 'status_id'=>10, 'user_id'=>123]);
        $items = Item::factory()->create(['id'=>4, 'language'=>'nl', 'status_id'=>99, 'user_id'=>123]);
        $items = Item::factory()->create(['id'=>5, 'language'=>'nl', 'status_id'=>20, 'user_id'=>123]);
        
        App::setLocale('nl');

        $response = $this->getJson('/api/v1/nl/items/all_markers');
        $response
            ->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_find_item__fails_for_incorrect_input()
    {
        $items = Item::factory()->count(3)->create();
        $item  = Item::factory()->create(['id'=>123, 'language'=>'nl']);

        $response = $this->getJson('/api/v1/nl/items/1g79qna310ddf5177613921');

        $response
            ->assertStatus(404);
    }

    public function test_find_item_without_item_type_id()
    {
        $items = Item::factory()->count(3)->create();
        $item  = Item::factory()->create(['id'=>123, 'language'=>'nl', 'item_type_id'=>10]);

        $response = $this->getJson('/api/v1/nl/items/123');

        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json
                ->count(14)    
                ->where('id', 123)
                ->where('language', 'nl')
                ->etc()
            );
    }

    public function test_find_item_with_item_type_id()
    {
        $items = Item::factory()->count(3)->create();
        $item  = Item::factory()->create(['id'=>123, 'language'=>'nl', 'item_type_id'=>20]);

        $response = $this->getJson('/api/v1/nl/items/123?item_type_id=20');

        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json
                ->count(14)    
                ->where('id', 123)
                ->where('language', 'nl')
                ->etc()
            );
    }

    public function test_get_all_items_of_type()
    {
        $user = User::factory()->create(['id'=>123]);

        $items = Item::factory()->create(['id'=>1, 'language'=>'nl', 'item_type_id'=>10, 'user_id'=>123]);
        $items = Item::factory()->create(['id'=>1, 'language'=>'en', 'item_type_id'=>10, 'user_id'=>123]);
        $items = Item::factory()->create(['id'=>2, 'language'=>'nl', 'item_type_id'=>20, 'user_id'=>123]);
        $items = Item::factory()->create(['id'=>2, 'language'=>'en', 'item_type_id'=>20, 'user_id'=>123]);
        $items = Item::factory()->create(['id'=>3, 'language'=>'nl', 'item_type_id'=>10, 'user_id'=>123]);
        $items = Item::factory()->create(['id'=>4, 'language'=>'nl', 'item_type_id'=>20, 'user_id'=>123]);
        
        App::setLocale('nl');
        
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

    public function test_get_all_items_of_type_and_two_statuses()
    {
        $user = User::factory()->create(['id'=>123]);

        $items = Item::factory()->create(['id'=>1, 'language'=>'nl', 'item_type_id'=>10, 'status_id'=>1, 'user_id'=>123]);
        $items = Item::factory()->create(['id'=>1, 'language'=>'en', 'item_type_id'=>10, 'status_id'=>1, 'user_id'=>123]);
        $items = Item::factory()->create(['id'=>2, 'language'=>'nl', 'item_type_id'=>20, 'status_id'=>10, 'user_id'=>123]);
        $items = Item::factory()->create(['id'=>2, 'language'=>'en', 'item_type_id'=>20, 'status_id'=>10, 'user_id'=>123]);
        $items = Item::factory()->create(['id'=>3, 'language'=>'nl', 'item_type_id'=>10, 'status_id'=>10, 'user_id'=>123]);
        $items = Item::factory()->create(['id'=>4, 'language'=>'nl', 'item_type_id'=>20, 'status_id'=>20, 'user_id'=>123]);
        
        App::setLocale('nl');
        
        $response = $this->getJson('/api/v1/nl/items?item_type_id=20&status_id=10,20');
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
