<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    
        $this->withoutMiddleware();
    }

    public function test_create_customer()
    {
        $response = $this->postJson('/api/customers/create', [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'phone' => '123-456-7890',
            'address' => '123 Elm Street',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'message' => 'Customer created successfully',
                 ]);

        $this->assertDatabaseHas('customers', [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'phone' => '123-456-7890',
            'address' => '123 Elm Street',
        ]);
    }

    public function test_get_all_customers()
    {
        Customer::create([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'phone' => '123-456-7890',
            'address' => '123 Elm Street',
        ]);

        Customer::create([
            'name' => 'Jane Smith',
            'email' => 'jane.smith@example.com',
            'phone' => '098-765-4321',
            'address' => '456 Oak Avenue',
        ]);

        $response = $this->getJson('/api/customers/index');

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'message' => 'All customers',
                 ])
                 ->assertJsonStructure([
                    'customers' => [
                        [
                            'id',
                            'name',
                            'email',
                            'phone',
                            'address',
                            'created_at',
                            'updated_at',
                        ]
                    ],
                 ]);
    }

    public function test_update_customer()
    {
        $customer = Customer::create([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'phone' => '123-456-7890',
            'address' => '123 Elm Street',
        ]);

        $response = $this->putJson("/api/customers/update/{$customer->id}", [
            'name' => 'John Doe Jr.',
            'email' => 'john.doe.jr@example.com',
            'phone' => '321-654-0987',
            'address' => '789 Pine Road',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'message' => 'Customer updated successfully',
                 ]);

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'name' => 'John Doe Jr.',
            'email' => 'john.doe.jr@example.com',
            'phone' => '321-654-0987',
            'address' => '789 Pine Road',
        ]);
    }

    public function test_delete_customer()
    {
        $customer = Customer::create([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'phone' => '123-456-7890',
            'address' => '123 Elm Street',
        ]);

        $response = $this->deleteJson("/api/customers/delete/{$customer->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'message' => 'Customer deleted successfully',
                 ]);

        $this->assertDatabaseMissing('customers', [
            'id' => $customer->id,
        ]);
    }
}
