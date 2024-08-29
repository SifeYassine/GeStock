<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Order;
use App\Models\Role;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        Role::create(['name' => 'Admin', 'description' => 'Administrator role']);
        Role::create(['name' => 'User', 'description' => 'Regular user role']);
        
        // Create a user
        User::create([
            'username' => 'testuser',
            'password' => Hash::make('password123'),
            'role_id' => Role::where('name', 'Admin')->first()->id,
        ]);

        // Create a customer
        Customer::create([
            'name' => 'Test Customer',
            'email' => 'customer@test.com',
            'phone' => '123-456-7890',
            'address' => '123 Test St',
        ]);

        $this->withoutMiddleware();
    }

    public function test_create_order()
    {
        $response = $this->post('/api/orders/create', [
            'total_price' => 150.00,
            'status' => 'processing',
            'customer_id' => Customer::first()->id,
            'user_id' => User::first()->id,
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'message' => 'Order created successfully',
                 ]);

        $this->assertDatabaseHas('orders', [
            'total_price' => 150.00,
            'status' => 'processing',
            'customer_id' => Customer::first()->id,
            'user_id' => User::first()->id,
        ]);
    }

    public function test_get_all_orders()
    {
        Order::create([
            'total_price' => 150.00,
            'status' => 'processing',
            'customer_id' => Customer::first()->id,
            'user_id' => User::first()->id,
        ]);

        $response = $this->get('/api/orders/index');

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'message' => 'All orders',
                 ])
                 ->assertJsonStructure([
                     'orders' => [
                         [
                             'id',
                             'total_price',
                             'status',
                             'customer_id',
                             'user_id',
                             'created_at',
                             'updated_at',
                         ]
                     ],
                 ]);
    }

    public function test_update_order()
    {
        $order = Order::create([
            'total_price' => 150.00,
            'status' => 'processing',
            'customer_id' => Customer::first()->id,
            'user_id' => User::first()->id,
        ]);

        $response = $this->put("/api/orders/update/{$order->id}", [
            'total_price' => 200.00,
            'status' => 'completed',
            'customer_id' => Customer::first()->id,
            'user_id' => User::first()->id,
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'message' => 'Order updated successfully',
                 ]);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'total_price' => 200.00,
            'status' => 'completed',
            'customer_id' => Customer::first()->id,
            'user_id' => User::first()->id,
        ]);
    }

    public function test_delete_order()
    {
        $order = Order::create([
            'total_price' => 150.00,
            'status' => 'processing',
            'customer_id' => Customer::first()->id,
            'user_id' => User::first()->id,
        ]);

        $response = $this->deleteJson("/api/orders/delete/{$order->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'message' => 'Order deleted successfully',
                 ]);

        $this->assertDatabaseMissing('orders', [
            'id' => $order->id,
        ]);
    }
}
