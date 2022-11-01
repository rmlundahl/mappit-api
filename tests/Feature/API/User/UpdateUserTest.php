<?php

namespace Tests\Feature\API\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdateUserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_update_user__administrator_can_update_author()
    {
        $user = User::factory()->create(['role'=>'administrator']);
        $author = User::factory()->create(['id'=>100, 'name'=>'Old name', 'email'=>'test@example.org']);
               
        $this->actingAs($user)
            ->json('PUT', '/api/v1/users/100', [
                'id' => 100,
                'name' => 'Updated name',
                'email'=>'updated@example.org',
            ])->assertStatus( 200 );
        
        
        $this->assertDatabaseHas('users', [
            'id' => 100,
            'name' => 'Updated name',
            'email'=>'updated@example.org',
            ]);
    }

    public function test_update_user__author_can_not_update_author()
    {
        $user = User::factory()->create(['role'=>'author', 'is_group_admin'=>0]);
        $author = User::factory()->create(['id'=>100, 'name'=>'Old name', 'email'=>'test@example.org']);
               
        $this->actingAs($user)
            ->json('PUT', '/api/v1/users/100', [
                'id' => 100,
                'name' => 'Updated name',
                'email'=>'updated@example.org',
            ])->assertStatus( 403 );
        
        
        $this->assertDatabaseHas('users', [
            'id' => 100,
            'name' => 'Old name',
            'email'=>'test@example.org',
            ]);
        
    }

    public function test_update_user__author_is_group_admin_can_update_author()
    {
        $user = User::factory()->create(['role'=>'author', 'is_group_admin'=>1]);
        $author = User::factory()->create(['id'=>100, 'name'=>'Old name', 'email'=>'test@example.org', 'role'=>'author']);
               
        $this->actingAs($user)
            ->json('PUT', '/api/v1/users/100', [
                'id' => 100,
                'name' => 'Updated name',
                'email'=>'updated@example.org',
                'role'=>'author'
            ])->assertStatus( 200 );
        
        
        $this->assertDatabaseHas('users', [
            'id' => 100,
            'name' => 'Updated name',
            'email'=>'updated@example.org',
            ]);

    }

    public function test_update_user__author_is_group_admin_can_not_update_role()
    {
        $user = User::factory()->create(['role'=>'author', 'is_group_admin'=>1]);
        $author = User::factory()->create(['id'=>100, 'name'=>'Old name', 'email'=>'test@example.org', 'role'=>'author']);
               
        $this->actingAs($user)
            ->json('PUT', '/api/v1/users/100', [
                'id' => 100,
                'name' => 'Updated name',
                'email'=> 'updated@example.org',
                'role'=> 'editor',
            ])->assertStatus( 403 );
        
        
        $this->assertDatabaseHas('users', [
            'id' => 100,
            'name' => 'Old name',
            'email'=>'test@example.org',
            'role'=>'author',
            ]);

    }
    
}
