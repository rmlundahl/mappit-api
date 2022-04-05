<?php

namespace Tests\API\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

use App\Models\User;
use App\Models\Group;
use DB;

class IndexUsersTest extends TestCase
{
    use RefreshDatabase;

    public function test_index__show_no_users_if_unauthenticated()
    {
        
        $response = $this->getJson('/api/v1/users');
        $response
            ->assertStatus(401);
    }

    public function test_index__show_no_users_to_author()
    {
        $this->seed();
           
        $users = User::factory()->count(5)->create();
        $user = User::factory()->create(['role'=>'author']);
        $this->actingAs($user);

        $response = $this->getJson('/api/v1/users');
        $response
            ->assertStatus(403);
    }

    public function test_index__see_users_from_group()
    {
        $group0 = Group::factory()->create(['id'=>1,'parent_id'=>null]); // root
        $group1 = Group::factory()->create(['id'=>2,'parent_id'=>1]);
        $group2 = Group::factory()->create(['id'=>3,'parent_id'=>1]);
        
        $editor1 = User::factory()->create(['id'=>1,   'group_id'=>2, 'role'=>'editor']);
       
        $author1 = User::factory()->create(['id'=>101, 'group_id'=>2]);
        $author2 = User::factory()->create(['id'=>102, 'group_id'=>2]);
        $author3 = User::factory()->create(['id'=>103, 'group_id'=>3]);
        $author3 = User::factory()->create(['id'=>104, 'group_id'=>4]);
       
        $response = $this->actingAs($editor1)->get('/api/v1/users_from_group/2');

        $response->assertStatus(200)
        ->assertJsonCount(3)
        ->assertSeeInOrder(['1','101','102'])
        ->assertDontSee('"id":103');
    }

    public function _test_index__group_admin_sees_own_users()
    {
        // $this->seed();

        $author1 = User::factory()->create(['id'=>101, 'group_id'=>2]);
        $author2 = User::factory()->create(['id'=>102, 'group_id'=>2]);
        $author3 = User::factory()->create(['id'=>103, 'group_id'=>3]);

        $group_admin = User::factory()->create(['id'=>201, 'group_id'=>2, 'is_group_admin'=>1]);
        
        $response = $this->actingAs($group_admin)->get('/api/v1/users_from_group/2');

        $response->assertStatus(200)
        ->assertSeeInOrder(['101','102'])
        ->assertDontSee('103');
    }

    public function _test_load_all_Users__from_a_language()
    {
        $Users = User::factory()->create(['id'=>1, 'language'=>'nl']);
        $Users = User::factory()->create(['id'=>1, 'language'=>'en']);
        $Users = User::factory()->create(['id'=>2, 'language'=>'nl']);
        $Users = User::factory()->create(['id'=>2, 'language'=>'en']);
        $Users = User::factory()->create(['id'=>3, 'language'=>'nl']);
        
        \App::setLocale('en');
        
        $response = $this->getJson('/api/v1/nl/Users');
        $response
            ->assertStatus(200)
            ->assertJsonCount(2);
    }

    public function _test_find_User()
    {
        $Users = User::factory()->count(3)->create();
        $User  = User::factory()->create(['id'=>123, 'language'=>'nl']);

        $response = $this->getJson('/api/v1/nl/Users/123');

        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json
                ->count(11)    
                ->where('id', 123)
                ->where('language', 'nl')
                ->etc()
            );
    }

}
