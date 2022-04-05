<?php

namespace Tests\API\User;

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

    public function test_create_user()
    {
        $user = User::factory()->create();
        
        $password = Hash::make('MySecretPassword123');

        $this->actingAs($user)
            ->json('POST', '/api/v1/users', [
                'name' => 'New Name',
                'email' => 'test@example.org',
                'password' => $password,
                'group_id' => 1,
                'is_group_admin' => 1,
                'status_id' => 20,
            ])->assertStatus( 201 );
        
        
        $this->assertDatabaseHas('users', [
            'name' => 'New Name',
            'email' => 'test@example.org',
            'password' => $password,
            'group_id' => 1,
            'is_group_admin' => 1,
            'status_id' => 20
            ]);
    }
}
