<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create roles
        Role::create(['name' => 'Admin', 'description' => 'Administrator role']);
        Role::create(['name' => 'User', 'description' => 'Regular user role']);
    }

    public function test_user_can_register()
    {
        $response = $this->postJson('/api/auth/register', [
            'username' => 'testuser',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'message' => 'User registered successfully',
                 ]);

        $this->assertDatabaseHas('users', [
            'username' => 'testuser',
        ]);
    }

    public function test_user_can_login()
    {
        User::create([
            'username' => 'loginuser',
            'password' => Hash::make('password123'),
            'role_id' => Role::where('name', 'User')->first()->id,
        ]);

        $response = $this->postJson('/api/auth/login', [
            'username' => 'loginuser',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'message' => 'User logged in successfully',
                 ])
                 ->assertJsonStructure([
                     'access_token',
                     'token_type',
                 ]);
    }

    public function test_user_can_logout()
    {
        $user = User::create([
            'username' => 'logoutuser',
            'password' => Hash::make('password123'),
            'role_id' => Role::where('name', 'User')->first()->id,
        ]);

        $token = $user->createToken('token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/auth/logout');

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'message' => 'User logged out successfully',
                 ]);

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }
}
