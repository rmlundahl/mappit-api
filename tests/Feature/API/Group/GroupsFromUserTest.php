<?php

namespace Tests\Feature\API\Group;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

use App\Models\User;
use App\Models\Group;
use DB;

class GroupsFromUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_groups_from_user__show_no_groups_if_unauthenticated()
    {
        
        $response = $this->getJson('/api/v1/groups_from_user');
        $response
            ->assertStatus(401);
    }

    public function test_groups_from_user__show_groups_to_author()
    {
        $group0 = Group::factory()->create(['id'=>1,'parent_id'=>null]); // root
        $group1 = Group::factory()->create(['id'=>101,'parent_id'=>1]);
        $group2 = Group::factory()->create(['id'=>102,'parent_id'=>101]);
        $group2 = Group::factory()->create(['id'=>201,'parent_id'=>1]);

        $author0 = User::factory()->create(['id'=>1, 'group_id'=>101, 'role'=>'author', 'is_group_admin'=>1]);
        
        $response = $this->actingAs($author0)->get('/api/v1/groups_from_user');

        $response
            ->assertStatus(200)
            ->assertJsonCount(2)
            ->assertSeeInOrder(['101','102'])
            ->assertDontSee('201');
    }

}
