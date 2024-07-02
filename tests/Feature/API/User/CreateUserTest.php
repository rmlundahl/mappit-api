<?php

namespace Tests\Feature\API\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateUserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_create_user__administrator_can_create_author(): void
    {
        $user = User::factory()->create(['role'=>'administrator']);
        
        $password = Hash::make('MySecretPassword123');
       
        $this->actingAs($user)
            ->json('POST', '/api/v1/users', [
                'name' => 'New Name',
                'email' => 'test@example.org',
                'password' => $password,
                'locale' => 'nl',
                'group_id' => 1,
                'is_group_admin' => 1,
                'role' => 'author',
                'status_id' => 20,
            ])->assertStatus( 201 );
        
        
        $this->assertDatabaseHas('users', [
            'name' => 'New Name',
            'email' => 'test@example.org',
            'locale' => 'nl',
            'group_id' => 1,
            'is_group_admin' => 1,
            'role' => 'author',
            'status_id' => 20
            ]);
    }

    public function test_create_user__author_can_not_create_author(): void
    {
        $user = User::factory()->create(['role'=>'author', 'is_group_admin'=>0]);
        
        $password = Hash::make('MySecretPassword123');
       
        $this->actingAs($user)
            ->json('POST', '/api/v1/users', [
                'name' => 'New Name',
                'email' => 'test@example.org',
                'password' => $password,
                'group_id' => 1,
                'is_group_admin' => 0,
                'role' => 'author',
                'status_id' => 20,
            ])->assertStatus( 403 );
        
    }

    public function test_create_user__author_is_group_admin_can_create_author(): void
    {
        $user = User::factory()->create(['role'=>'author', 'is_group_admin'=>1]);
        
        $password = Hash::make('MySecretPassword123');
       
        $this->actingAs($user)
            ->json('POST', '/api/v1/users', [
                'name' => 'New Name',
                'email' => 'test@example.org',
                'password' => $password,
                'group_id' => 1,
                'is_group_admin' => 0,
                'role' => 'author',
                'status_id' => 20,
            ])->assertStatus( 201 );
        
        
        $this->assertDatabaseHas('users', [
            'name' => 'New Name',
            'email' => 'test@example.org',
            'group_id' => 1,
            'is_group_admin' => 0,
            'role' => 'author',
            'status_id' => 20
            ]);
    }

    public function test_create_user__author_is_group_admin_can_not_create_administrator(): void
    {
        $user = User::factory()->create(['role'=>'author', 'is_group_admin'=>1]);
        
        $password = Hash::make('MySecretPassword123');
       
        $this->actingAs($user)
            ->json('POST', '/api/v1/users', [
                'name' => 'New Name',
                'email' => 'test@example.org',
                'password' => $password,
                'group_id' => 1,
                'is_group_admin' => 0,
                'role' => 'administrator',
                'status_id' => 20,
            ])->assertStatus( 403 );
       
    }
}
